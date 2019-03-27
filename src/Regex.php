<?php

namespace Lavibi\Popoya;

class Regex extends AbstractRegexValidator
{
    public function match($regex)
    {
        $this->options['regex'] = $regex;
    }
}
