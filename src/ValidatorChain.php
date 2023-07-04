<?php

namespace Lavibi\Popoya;

use \InvalidArgumentException;

/**
 * Class ValidatorChain
 *
 * @package Lavibi\Popoya
 *
 * @method ValidatorChain sameAs(mixed $value)
 * @method ValidatorChain notSameAs(mixed $value)
 * @method ValidatorChain notEmpty() Check not empty string
 * @method ValidatorChain isUpload()
 * @method ValidatorChain isInteger()
 * @method ValidatorChain isImage()
 */
class ValidatorChain extends AbstractValidator
{
    /**
     * @var ValidatorInterface[]
     */
    protected $validators = [];

    /**
     * List of special methods of validators to set option value.
     * Each method belongs only validator.
     * Use as shortcut for addValidator method
     *
     * @var array
     */
    protected $setOptionMethods = [
        'sameAs' => '\Lavibi\Popoya\Same',
        'notSameAs' => '\Lavibi\Popoya\NotSame'
    ];

    /**
     * List of special methods of no option validators
     * Each method belongs only validator.
     * Use as shortcut for addValidator method
     *
     * @var array
     */
    protected $noOptionValidator = [
        'notEmpty' => '\Lavibi\Popoya\NotEmptyString',
        'isUpload' => '\Lavibi\Popoya\Upload',
        'isImage' => '\Lavibi\Popoya\Image',
        'isInteger' => '\Lavibi\Popoya\Integer'
    ];

    /**
     *
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (isset($this->noOptionValidator[$name])) {
            $validatorClass = $this->noOptionValidator[$name];
            $this->validators[$validatorClass] = new $validatorClass();
            return $this;
        }

        if (!isset($this->setOptionMethods[$name])) {
            throw new InvalidArgumentException('Set option method ' . $name . ' is not support');
        }

        $validatorClass = $this->setOptionMethods[$name];

        if (!isset($this->validators[$validatorClass])) {
            $this->validators[$validatorClass] = new $validatorClass();
        }

        call_user_func_array([$this->validators[$validatorClass], $name], $arguments);

        return $this;
    }

    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[get_class($validator)] = $validator;

        return $this;
    }

    public function reset()
    {
        $this->validators = [];

        parent::reset();

        return $this;
    }

    public function isValid($value)
    {
        $this->value = $this->standardValue = $value;

        foreach ($this->validators as $validator) {
            $result = $validator->isValid($this->standardValue);

            if (!$result) {
                $this->setErrorCode($validator->getMessageCode())->setErrorMessage($validator->getMessage());
                return false;
            }

            $this->standardValue = $validator->getStandardValue();
        }

        return true;
    }

    protected function setErrorMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    protected function setErrorCode($code)
    {
        $this->messageCode = $code;

        return $this;
    }
}
