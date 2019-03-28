<?php

namespace Lavibi\Popoya;

class ImageUpload extends UploadFile
{
    const MAX_WIDTH = 'max_width';
    const MAX_HEIGHT = 'max_height';

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
        if (!parent::isValid($value)) {
            return false;
        }

        $image = $this->isImageFile($value['tmp_name']);

        if (!$image) {
            return false;
        }

        if (!$this->isValidImageMaxSize($image['width'], $image['height'])) {
            return false;
        }

        $this->standardValue['image'] = $image;

        return true;
    }

    protected function isImageFile($filePath)
    {
        $size = getimagesize($filePath);

        if ($size === false) {
            $this->setError(self::NOT_VALID_TYPE);
            return false;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
        ];
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

        $this->defaultOptions['type'] = [
            'image/gif',
            'image/jpg',
            'image/jpeg',
            'image/jpe',
            'image/pjpeg',
            'image/png',
            'img/x-png'
        ];

        $this->defaultOptions['size'] = [
            'width' => 0,
            'height' => 0,
        ];

        $this->messages[self::NOT_VALID_TYPE] = 'Uploaded file was not image type.';
        $this->messages[self::MAX_WIDTH] = 'Image width is too large.';
        $this->messages[self::MAX_HEIGHT] = 'Image height is too large.';
    }
}
