<?php

namespace Lavibi\Popoya;

class NotSame extends AbstractCompareValidator
{
    const E_SAME = 'same';

    protected $messages = [
        self::E_SAME => 'The given value is same with compared value.'
    ];

    /**
     * Set compared value
     *
     * @param $value
     *
     * @return $this
     */
    public function notSameAs($value)
    {
        $this->options['compared_value'] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function compare()
    {
        $result = $this->value !== $this->options['compared_value'];

        if (!$result) {
            $this->setError(self::E_SAME);
        }

        return $result;
    }
}
