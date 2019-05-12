<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\NotEmptyString;
use Lavibi\Popoya\Exception\MissingOptionException;

final class NotEmptyStringTest extends TestCase
{
    public function testOptions()
    {
        $notEmptyStringValidator = new NotEmptyString();
        $this->assertSame(['trim' => true], $notEmptyStringValidator->getOptions());
    }

    public function testInValidNotEmptyString()
    {
        $notEmptyStringValidator = new NotEmptyString();
        $this->assertFalse($notEmptyStringValidator->isValid(5));
        $this->assertFalse($notEmptyStringValidator->isValid(''));
        $this->assertFalse($notEmptyStringValidator->isValid(' '));
    }

    public function testValidNotEmptyString()
    {
        $notEmptyStringValidator = new NotEmptyString();
        $this->assertTrue($notEmptyStringValidator->isValid('6'));
        $this->assertTrue($notEmptyStringValidator->isValid('a'));
        $this->assertTrue($notEmptyStringValidator->isValid(' a '));
    }

    public function testWithNotTrim()
    {
        $notEmptyStringValidator = new NotEmptyString();
        $notEmptyStringValidator->setOptions(['trim' => false]);
        $this->assertTrue($notEmptyStringValidator->isValid('     '));
        $this->assertFalse($notEmptyStringValidator->isValid([]));
        $this->assertFalse($notEmptyStringValidator->isValid(0));
    }

    public function testMessage()
    {
        $notEmptyStringValidator = new NotEmptyString();
        $this->assertFalse($notEmptyStringValidator->isValid(5));
        $this->assertSame('not_string', $notEmptyStringValidator->getMessageCode());
        $this->assertSame('The given value is not string.', $notEmptyStringValidator->getMessage());

        $this->assertFalse($notEmptyStringValidator->isValid(''));
        $this->assertSame('empty', $notEmptyStringValidator->getMessageCode());
        $this->assertSame('The given value is empty string.', $notEmptyStringValidator->getMessage());
    }
}
