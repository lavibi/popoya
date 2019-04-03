<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Image;
use Nyholm\Psr7\UploadedFile;
use Nyholm\Psr7\Stream;

class ImageTest extends TestCase
{
    public function testNotValidImage()
    {
        $imageUploadValidator = new Image();

        $imageUploadValidator->reset();
        $this->assertFalse($imageUploadValidator->isValid([]));
        $this->assertSame('Given data was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_image', $imageUploadValidator->getMessageCode());

        $imageUploadValidator->reset();
        $this->assertFalse($imageUploadValidator->isValid('a'));
        $this->assertSame('Given data was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_image', $imageUploadValidator->getMessageCode());

        $imageUploadValidator->reset();
        $this->assertFalse($imageUploadValidator->isValid(1));
        $this->assertSame('Given data was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_image', $imageUploadValidator->getMessageCode());

        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $imageUploadValidator->reset();
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));
        $this->assertSame('Given data was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_image', $imageUploadValidator->getMessageCode());
    }

    public function testNotValidImageFromPSR7UploadFile()
    {
        $uploadFile = new UploadedFile('./tests/file/phone.svg', 63837, 0);

        $imageUploadValidator = new Image();
        $this->assertFalse($imageUploadValidator->isValid($uploadFile));
        $this->assertSame('Given data was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_image', $imageUploadValidator->getMessageCode());
    }

    public function testValidImage()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
            'error' => \UPLOAD_ERR_OK
        ];

        $imageUploadValidator = new Image();
        $this->assertTrue($imageUploadValidator->isValid($uploadFileData));
    }

    public function testValidImageFromPSR7UploadFile()
    {
        $uploadFile = new UploadedFile('./tests/file/gerald-dicen-1463556-unsplash.jpg', 63837, 0);

        $imageUploadValidator = new Image();
        $this->assertTrue($imageUploadValidator->isValid($uploadFile));
    }

    public function testUploadWithSize()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
            'error' => UPLOAD_ERR_OK
        ];

        $imageUploadValidator = new Image();
        $this->assertTrue($imageUploadValidator->isValid($uploadFileData));

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(499);
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));
        $this->assertSame('Image width is too large.', $imageUploadValidator->getMessage());
        $this->assertSame('max_width', $imageUploadValidator->getMessageCode());

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxHeight(624);
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));
        $this->assertSame('Image height is too large.', $imageUploadValidator->getMessage());
        $this->assertSame('max_height', $imageUploadValidator->getMessageCode());

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(500);
        $imageUploadValidator->hasMaxHeight(625);
        $this->assertTrue($imageUploadValidator->isValid($uploadFileData));

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(300);
        $imageUploadValidator->hasMaxHeight(325);
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(800);
        $imageUploadValidator->hasMaxHeight(900);
        $this->assertTrue($imageUploadValidator->isValid($uploadFileData));

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(200);
        $imageUploadValidator->hasMaxHeight(900);
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));

        $imageUploadValidator->reset();
        $imageUploadValidator->hasMaxWidth(800);
        $imageUploadValidator->hasMaxHeight(300);
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));
    }
}
