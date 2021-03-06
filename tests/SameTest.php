<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Same;
use Lavibi\Popoya\Exception\MissingOptionException;

final class SameTest extends TestCase
{
    public function testOptions()
    {
        $sameValidator = new Same();
        $this->assertSame([], $sameValidator->getOptions());

        $sameValidator->sameAs('5');
        $this->assertSame(1, count($sameValidator->getOptions()));
        $this->assertSame('5', $sameValidator->getOptions()['compared_value']);

        $sameValidator->setOptions([
            'compared_value' => '7'
        ]);
        $this->assertSame('7', $sameValidator->getOptions()['compared_value']);
    }

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

    public function testNotSameMessage()
    {
        $sameValidator = new Same();
        $this->assertFalse($sameValidator->sameAs('5')->isValid(5));
        $this->assertSame('not_same', $sameValidator->getMessageCode());
        $this->assertSame('The given value is not same with compared value.', $sameValidator->getMessage());
    }

    public function testSame()
    {
        $sameValidator = new Same();
        $this->assertTrue($sameValidator->sameAs(5)->isValid(5));
        $this->assertTrue($sameValidator->sameAs('a')->isValid('a'));
        $this->assertTrue($sameValidator->sameAs(['a' => 2])->isValid(['a' => 2]));
    }
}
