<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\NotSame;
use Lavibi\Popoya\Exception\MissingOptionException;

final class NotSameTest extends TestCase
{
    public function testOptions()
    {
        $notSameValidator = new NotSame();
        $this->assertSame([], $notSameValidator->getOptions());

        $notSameValidator->notSameAs('5');
        $this->assertSame(1, count($notSameValidator->getOptions()));
        $this->assertSame('5', $notSameValidator->getOptions()['compared_value']);

        $notSameValidator->setOptions([
            'compared_value' => '7'
        ]);
        $this->assertSame('7', $notSameValidator->getOptions()['compared_value']);
    }

    public function testMissingRequireOption()
    {
        $this->expectException(MissingOptionException::class);

        $notSameValidator = new NotSame();
        $notSameValidator->isValid('a');
    }

    public function testSame()
    {
        $notSameValidator = new NotSame();
        $this->assertFalse($notSameValidator->notSameAs(5)->isValid(5));
        $this->assertFalse($notSameValidator->notSameAs('a')->isValid('a'));
        $this->assertFalse($notSameValidator->notSameAs(['a' => 2])->isValid(['a' => 2]));
    }

    public function testSameMessage()
    {
        $notSameValidator = new NotSame();
        $this->assertFalse($notSameValidator->notSameAs(5)->isValid(5));
        $this->assertSame('same', $notSameValidator->getMessageCode());
        $this->assertSame('The given value is same with compared value.', $notSameValidator->getMessage());
    }

    public function testNotSame()
    {
        $sameValidator = new NotSame();
        $this->assertTrue($sameValidator->notSameAs(5)->isValid('5'));
        $this->assertTrue($sameValidator->notSameAs('a')->isValid('ab'));
        $this->assertTrue($sameValidator->notSameAs(['a' => 2])->isValid(['a' => 3]));
    }
}
