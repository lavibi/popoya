<?php

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Same;
use Lavibi\Popoya\Exception\MissingOptionException;

final class SameTest extends TestCase
{
    /**
     *
     */
    public function testMissingRequireOption()
    {
        $this->expectException(MissingOptionException::class);

        $sameValidator = new Same();
        $sameValidator->isValid('a');
    }

    public function testNotSame()
    {
        $sameValidator = new Same();
        $this->assertFalse($sameValidator->sameAs(5)->isValid('5'));
        $this->assertFalse($sameValidator->sameAs('a')->isValid('ab'));
        $this->assertFalse($sameValidator->sameAs(['a' => 2])->isValid(['a' => 3]));
    }

    public function testSame()
    {
        $sameValidator = new Same();
        $this->assertTrue($sameValidator->sameAs(5)->isValid(5));
        $this->assertTrue($sameValidator->sameAs('a')->isValid('a'));
        $this->assertTrue($sameValidator->sameAs(['a' => 2])->isValid(['a' => 2]));
    }
}
