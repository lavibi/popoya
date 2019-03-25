<?php

namespace Lavibi\Popoya;

use Lavibi\Popoya\Exception\MissingOptionException;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * Input value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Filter value for valid input value.
     *
     * @var mixed
     */
    protected $standardValue;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array();

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
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Default options.
     *
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * Required options.
     *
     * @var string[]
     */
    protected $requiredOptions = [];

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Run validator
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke($value)
    {
        return $this->isValid($value);
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return AbstractValidator
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Check missing options.
     *
     * @throws MissingOptionException
     */
    public function checkMissingOptions()
    {
        foreach ($this->requiredOptions as $option) {
            if (!array_key_exists($option, $this->options)) {
                throw new MissingOptionException('Missing option ' . $option . ' for validator');
            }
        }
    }

    /**
     * Reset validator.
     *
     * @return AbstractValidator
     */
    public function reset()
    {
        $this->init();

        $this->options = $this->defaultOptions;
        $this->standardValue = null;

        return $this;
    }

    /**
     * Set error.
     *
     * @param string $code
     *
     * @return AbstractValidator
     */
    protected function setError($code)
    {
        $this->message = $this->messages[$code];
        $this->messageCode = $code;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Get standard value
     *
     * @return mixed
     */
    public function getStandardValue()
    {
        return $this->standardValue;
    }

    /**
     * Init method.
     */
    protected function init()
    {
    }
}
