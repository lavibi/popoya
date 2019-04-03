<?php

namespace Lavibi\Popoya;

use Psr\Http\Message\UploadedFileInterface;
use SplFileInfo;

class Size extends AbstractValidator
{
    const GREATER_THAN_MAX = 'greater_than_max';
    const SMALLER_THAN_MIN = 'smaller_than_min';
    const NOT_VALID_SIZE = 'not_valid_size';

    protected $messages = [
        self::GREATER_THAN_MAX => 'Size of file is too large.',
        self::SMALLER_THAN_MIN => 'Size of file is too small.',
        self::NOT_VALID_SIZE => 'Give value is not valid size.'
    ];

    /**
     * @var UploadedFileInterface|SplFileInfo|array|mixed
     */
    protected $value;

    protected $defaultOptions = [
        'max' => 0,
        'min' => 0,
    ];

    protected $requiredOptions = [
        'max',
        'min'
    ];

    public function hasMaxSize($maxSize)
    {
        $this->options['max'] = (int) $maxSize;

        return $this;
    }

    public function hasMinSize($minSize)
    {
        $this->options['min'] = (int) $minSize;

        return $this;
    }

    public function isValid($value)
    {
        $this->value = $value;

        $size = $this->getSize();

        if ($size === false) {
            $this->setError(static::NOT_VALID_SIZE);
            return false;
        }

        if ($this->options['min'] > 0 && $this->options['min'] > $size) {
            $this->setError(self::SMALLER_THAN_MIN);
            return false;
        }

        if ($this->options['max'] > 0 && $this->options['max'] < $size) {
            $this->setError(self::GREATER_THAN_MAX);
            return false;
        }

        $this->standardValue = $this->value;
        return true;
    }

    protected function getSize()
    {
        $size = $this->value;

        if ($this->value instanceof UploadedFileInterface) {
            $size = $this->value->getSize();
        }

        if ($this->value instanceof SplFileInfo) {
            $size = $this->value->getSize();
        }

        if (is_array($this->value) && isset($this->value['size'])) {
            $size = $this->value['size'];
        }

        if (is_int($size)) {
            return (int) $size;
        }

        return false;
    }
}
