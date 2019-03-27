<?php

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Regex;
use Lavibi\Popoya\Exception\MissingOptionException;

final class RegexTest extends TestCase
{
    public function testOptions()
    {
        $regexValidator = new Regex();
        $this->assertSame([], $regexValidator->getOptions());

        $regexValidator->match('/^[a-z]$/');
        $this->assertSame(1, count($regexValidator->getOptions()));
        $this->assertSame('/^[a-z]$/', $regexValidator->getOptions()['regex']);

        $regexValidator->setOptions([
            'regex' => '/^[0-9]$/'
        ]);
        $this->assertSame('/^[0-9]$/', $regexValidator->getOptions()['regex']);

    }

    public function testMissingRequireOption()
    {
        $this->expectException(MissingOptionException::class);

        $regexValidator = new Regex();
        $regexValidator->isValid('a');
    }

    public function testNotMatch()
    {
        $regexValidator = new Regex();
        $this->assertFalse($regexValidator->match('/^[0-9]$/')->isValid(2));
        $this->assertFalse($regexValidator->match('/^[0-9]$/')->isValid('22'));
        $this->assertFalse($regexValidator->match('/^[0-9]$/')->isValid('a'));
    }

    public function testNotStringMessage()
    {
        $regexValidator = new Regex();
        $this->assertFalse($regexValidator->match('/5/')->isValid(5));
        $this->assertSame('not_string', $regexValidator->getMessageCode());
        $this->assertSame('The given value must be string.', $regexValidator->getMessage());
    }

    public function testNotMatchMessage()
    {
        $regexValidator = new Regex();
        $this->assertFalse($regexValidator->match('/^[a-z]$/')->isValid('5'));
        $this->assertSame('not_match', $regexValidator->getMessageCode());
        $this->assertSame('The given value is not match regex.', $regexValidator->getMessage());
    }

    public function testMatch()
    {
        $regexValidator = new Regex();
        $this->assertTrue($regexValidator->match('/^[0-9]$/')->isValid('2'));
    }
}
