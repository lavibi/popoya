<?php

namespace Lavibi\Popoya;

class ValidatorChain extends AbstractValidator
{
    /**
     * @var ValidatorInterface[]
     */
    protected $validators = [];

    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;

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
