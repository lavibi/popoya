<?php

namespace Lavibi\Popoya;

class Integer extends AbstractValidator
{
    const NOT_INTEGER = 'not_integer';
    const NOT_INTEGER_MESSAGE = 'The given value must be integer.';

    protected $messages = [
        self::NOT_INTEGER => self::NOT_INTEGER_MESSAGE
    ];

    public function isValid($value): bool
    {
        $this->value = $this->standardValue = $value;

        if (is_string($value)) {
            if (!preg_match('/^[1-9][0-9]*$|^0$/', $value)) {
                $this->setError(static::NOT_INTEGER);
                return false;
            }

            $this->standardValue = (int) $value;
            return true;
        }

        if (is_int($value)) {
            return true;
        }

        $this->setError(self::NOT_INTEGER);
        return false;
    }
}
