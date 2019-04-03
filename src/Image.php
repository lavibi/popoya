<?php

namespace Lavibi\Popoya;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class Image extends AbstractValidator
{
    const MAX_WIDTH = 'max_width';
    const MAX_HEIGHT = 'max_height';
    const NOT_VALID_IMAGE = 'not_valid_image';

    public function hasMaxWidth($width)
    {
        $this->options['size']['width'] = (int) $width;

        return $this;
    }

    public function hasMaxHeight($height)
    {
        $this->options['size']['height'] = (int) $height;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->value = $value;

        $image = $this->isImage();

        if (!$image) {
            return false;
        }

        if (!$this->isValidImageMaxSize($image['width'], $image['height'])) {
            return false;
        }

        $this->standardValue['image'] = $image;

        return true;
    }

    protected function isImage()
    {
        $file = $this->getFile();

        if ($file === false) {
            $this->setError(static::NOT_VALID_IMAGE);
            return false;
        }

        $size = @getimagesize($file);

        if ($size === false) {
            $this->setError(static::NOT_VALID_IMAGE);
            return false;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
        ];
    }

    protected function getFile()
    {
        $file = $this->value;

        if ($this->value instanceof UploadedFileInterface) {
            $file = $this->value->getStream();
        }

        if (is_array($this->value) && isset($this->value['tmp_name'])) {
            $file = $this->value['tmp_name'];
        }

        if (is_string($file)) {
            return $file;
        }

        if ($file instanceof StreamInterface) {
            return $file->getMetadata('uri');
        }

        return false;
    }

    protected function isValidImageMaxSize($width, $height)
    {
        if ($this->options['size']['width'] !== 0 && $width > $this->options['size']['width']) {
            $this->setError(self::MAX_WIDTH);
            return false;
        }

        if ($this->options['size']['height'] !== 0 && $height > $this->options['size']['height']) {
            $this->setError(self::MAX_HEIGHT);
            return false;
        }

        return true;
    }

    protected function init()
    {
        parent::init();

        $this->defaultOptions['size'] = [
            'width' => 0,
            'height' => 0,
        ];

        $this->messages[static::NOT_VALID_IMAGE] = 'Given data was not image type.';
        $this->messages[static::MAX_WIDTH] = 'Image width is too large.';
        $this->messages[static::MAX_HEIGHT] = 'Image height is too large.';
    }
}
