<?php

namespace Lavibi\Popoya;

abstract class AbstractCompareValidator extends AbstractValidator
{
    /**
     * Required options.
     *
     * @var string[]
     */
    protected $requiredOptions = [
        'compared_value'
    ];

    /**
     * @param mixed $value
     *
     * @return bool
     *
     * @throws Exception\MissingOptionException
     */
    public function isValid($value)
    {
        $this->value = $value;

        $this->checkMissingOptions();

        return $this->compare();
    }

    /**
     * Compare value
     *
     * @return bool
     */
    abstract protected function compare();
}
