<?php

namespace Lavibi\Popoya;

abstract class AbstractRegexValidator extends AbstractValidator
{
    const NOT_STRING = 'not_string';
    const NOT_MATCH = 'not_match';
    const NOT_STRING_MESSAGE = 'The given value must be string.';
    const NOT_MATCH_MESSAGE = 'The given value is not match regex.';

    protected $messages = [
        self::NOT_STRING => self::NOT_STRING_MESSAGE,
        self::NOT_MATCH => self::NOT_MATCH_MESSAGE
    ];

    protected $requiredOptions = [
        'regex'
    ];

    protected $notMatchCode = self::NOT_MATCH;

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->value = $this->standardValue = $value;

        if (!is_string($value)) {
            $this->setError(self::NOT_STRING);
            return false;
        }

        if (!preg_match($this->options['regex'], $value)) {
            $this->setError($this->notMatchCode);
            return false;
        }
    }
}
