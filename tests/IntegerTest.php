<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Integer;
use Lavibi\Popoya\Exception\MissingOptionException;

final class IntegerTest extends TestCase
{
    public function testNotIntegerString()
    {
        $integerValidator = new Integer();
        $this->assertFalse($integerValidator->isValid('i9009302039202'));
        $this->assertFalse($integerValidator->isValid('93920a'));
        $this->assertFalse($integerValidator->isValid('9394939 392949292939293'));
        $this->assertFalse($integerValidator->isValid('000'));
    }

    public function testIntegerString()
    {
        $integerValidator = new Integer();
        $this->assertTrue($integerValidator->isValid('9392039'));
        $this->assertTrue($integerValidator->isValid('0'));
    }

    public function testGetStandardValue()
    {
        $integerValidator = new Integer();

        $integerValidator->isValid('9392039');
        $this->assertTrue($integerValidator->getStandardValue() === 9392039);

        $integerValidator->isValid('0');
        $this->assertTrue($integerValidator->getStandardValue() === 0);
    }

    public function testGetError()
    {
        $integerValidator = new Integer();

        $integerValidator->isValid('a9392039');
        $this->assertTrue($integerValidator->getMessage() === Integer::NOT_INTEGER_MESSAGE);

        $integerValidator->isValid('00');
        $this->assertTrue($integerValidator->getMessageCode() === Integer::NOT_INTEGER);
    }
}
