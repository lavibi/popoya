<?php

namespace Lavibi\Popoya;

class Validator
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
     * Index array of data list need to validate
     *
     * 1 is required
     * 0 is optional
     *
     * @var []
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $values;

    /**
     * @param $name
     *
     * @return ValidatorChain
     */
    public function isRequired($name)
    {
        return $this->add($name, self::IS_REQUIRED);
    }

    /**
     * @param $name
     *
     * @return ValidatorChain
     */
    public function isOptional($name)
    {
        return $this->add($name, self::IS_OPTIONAL);
    }

    public function isValid($values)
    {
        foreach ($this->data as $name => $isRequired) {
            if ($isRequired && !isset($this->$values[$name])) {
                return false;
            }

            if (isset($this->$values[$name])) {
                $result = $this->dataValidatorChain[$name]->isValid($this->$values[$name]);
            }
        }

        return true;
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

        if (!isset($dataValidatorChain[$name])) {
            $this->dataValidatorChain[$name] = new ValidatorChain();
        }

        return $this->dataValidatorChain[$name];
    }
}
