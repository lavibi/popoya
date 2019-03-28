<?php

namespace Lavibi\Popoya;

interface ValidatorInterface
{
    /**
     * Validate
     *
     * @param mixed $value Given value to validate
     *
     * @return boolean
     */
    public function isValid($value);

    /**
     * Get error message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Get error code
     *
     * @return string
     */
    public function getMessageCode();

    /**
     * Get valid and filter value after validation
     * @return mixed
     */
    public function getStandardValue();
}
