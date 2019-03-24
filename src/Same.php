<?php

namespace Lavibi\Popoya;

class Same extends AbstractCompareValidator
{
    const E_NOT_SAME = 'not_same';

    protected $messages = [
        self::E_NOT_SAME => 'The given value is not same with compared value.'
    ];

    /**
     * Set compared value
     *
     * @param $value
     *
     * @return $this
     */
    public function sameAs($value)
    {
        $this->options['compared_value'] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function compare()
    {
        $result = $this->value === $this->options['compared_value'];

        if (!$result) {
            $this->setError(self::E_NOT_SAME);
        }

        return $result;
    }
}