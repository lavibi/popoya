<?php

namespace Lavibi\PopoyaTest;

use \InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\ValidatorChain;
use Lavibi\Popoya\NotSame;
use Lavibi\Popoya\Same;
use Lavibi\Popoya\Upload;
use Lavibi\Popoya\Image;
use Lavibi\Popoya\Exception\MissingOptionException;

class ValidatorChainTest extends TestCase
{
    public function testInvalidMethod()
    {
        $this->expectException(InvalidArgumentException::class);

        $validator = new ValidatorChain();
        $validator->fakeMethod();

        $this->expectException(InvalidArgumentException::class);

        $validator = new ValidatorChain();
        $validator->fakeMethod2('34', 4);
    }

    public function testValid()
    {
        $validator = new ValidatorChain();

        $notSamValidator = new NotSame();
        $notSamValidator->notSameAs('5');

        $validator->addValidator($notSamValidator);
        $this->assertTrue($validator->isValid(6));
    }

    public function testMissingRequireOption()
    {
        $this->expectException(MissingOptionException::class);

        $validator = new ValidatorChain();
        $sameValidator = new Same();
        $sameValidator->sameAs(5);
        $notSamValidator = new NotSame();

        $validator->addValidator($notSamValidator)->addValidator($sameValidator);
        $validator->isValid(5);
    }

    public function testValidValidatorBeforeMissingRequireOptionValidator()
    {
        $this->expectException(MissingOptionException::class);

        $validator = new ValidatorChain();
        $sameValidator = new Same();
        $sameValidator->sameAs(5);
        $notSamValidator = new NotSame();

        $validator->addValidator($sameValidator)->addValidator($notSamValidator);
        $validator->isValid(5);
    }

    public function testInValid()
    {
        $validator = new ValidatorChain();

        $notSamValidator = new NotSame();
        $notSamValidator->notSameAs(6);

        $sameValidator = new Same();
        $sameValidator->sameAs(6);

        $validator->addValidator($notSamValidator)->addValidator($sameValidator);
        $this->assertFalse($validator->isValid(6));
        $this->assertEquals(NotSame::E_SAME, $validator->getMessageCode());
        $this->assertEquals('The given value is same with compared value.', $validator->getMessage());

        $validator->reset()->addValidator($sameValidator)->addValidator($notSamValidator);
        $this->assertFalse($validator->isValid(5));
        $this->assertEquals(Same::E_NOT_SAME, $validator->getMessageCode());
        $this->assertEquals('The given value is not same with compared value.', $validator->getMessage());
    }

    public function testInValidWithNoOptionValidator()
    {
        $validator = new ValidatorChain();

        $validator->isUpload()->isImage();

        $this->assertFalse($validator->isValid(6));
        $this->assertEquals(Upload::NOT_UPLOAD_DATA, $validator->getMessageCode());

        $validator = new ValidatorChain();
        $validator->isImage()->isUpload();

        $this->assertFalse($validator->isValid(6));
        $this->assertEquals(Image::NOT_VALID_IMAGE, $validator->getMessageCode());
    }

    public function testAutoFunctionWork()
    {
        $validator = new ValidatorChain();
        $this->assertFalse($validator->notEmpty()->isValid(' '));
        $this->assertTrue($validator->notEmpty()->isValid('a'));
    }
}
