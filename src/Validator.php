<?php

namespace Lavibi\Popoya;

class Validator implements ValidatorInterface
{
    const IS_REQUIRED = 1;

    const IS_OPTIONAL = 0;

    /**
     * @var ValidatorChain
     */
    protected $chain;

    /**
     * @var ValidatorChain[]
     */
    protected $dataValidatorChain;

    /**
     * Error message
     *
     * @var string
     */
    protected $message;

    /**
     * Error code
     *
     * @var string
     */
    protected $messageCode;

    /**
     * Index array of data list need to validate
     *
     * 1 is required
     * 0 is optional
     *
     * @var string[]
     */
    protected $data;

    /**
     * @var mixed[]
     */
    protected $values;

    /**
     * @param $name
     *
     * @return ValidatorChain
     */
    public function isRequired($name)
    {
        return $this->add($name, static::IS_REQUIRED);
    }

    /**
     * @param $name
     *
     * @return ValidatorChain
     */
    public function isOptional($name)
    {
        return $this->add($name, static::IS_OPTIONAL);
    }

    public function isValid($values)
    {
        foreach ($this->data as $name => $isRequired) {
            if ($isRequired === static::IS_OPTIONAL) {
                if (!isset($values[$name])) {
                    continue;
                }
            }

            if (!isset($values[$name])) {
                return false;
            }

            if (!$this->dataValidatorChain[$name]->isValid($values[$name])) {
                return false;
            }
        }

        return true;
    }

    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }

    public function getMessageCode()
    {
        // TODO: Implement getMessageCode() method.
    }

    public function getStandardValue()
    {
        // TODO: Implement getStandardValue() method.
    }

    /**
     * @param $name
     * @param $isRequired
     *
     * @return ValidatorChain
     */
    public function add($name, $isRequired)
    {
        $this->data[$name] = $isRequired;

        if (!isset($this->dataValidatorChain[$name])) {
            $this->dataValidatorChain[$name] = new ValidatorChain();
        }

        return $this->dataValidatorChain[$name];
    }
}
