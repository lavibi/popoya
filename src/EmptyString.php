<?php

namespace Lavibi\Popoya;

class EmptyString extends AbstractValidator
{
    const E_NOT_EMPTY = 'empty';
    const E_NOT_STRING = 'not_string';

    protected $messages = [
        self::E_NOT_EMPTY => 'The given value is not empty string.',
        self::E_NOT_STRING => 'The given value is not string.'
    ];

    protected $defaultOptions = [
        'trim' => true
    ];

    public function isValid($value)
    {
        $this->standardValue = null;
        $this->value = $value;

        if (!is_string($this->value)) {
            $this->setError(static::E_NOT_STRING);
            return false;
        }

        if ($this->options['trim'] === true) {
            $this->value = trim($this->value);
        }

        if ($this->value !== '') {
            $this->setError(static::E_NOT_EMPTY);
            return false;
        }

        $this->standardValue = $this->value;
        return true;
    }
}
