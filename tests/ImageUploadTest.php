<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\ImageUpload;

class ImageUploadTest extends TestCase
{
    public function testUploadWithFileType()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $imageUploadValidator = new ImageUpload();
        $this->assertFalse($imageUploadValidator->isValid($uploadFileData));
        $this->assertSame('Uploaded file was not image type.', $imageUploadValidator->getMessage());
        $this->assertSame('not_valid_type', $imageUploadValidator->getMessageCode());
    }

    public function testUploadWithSize()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
            'error' => \UPLOAD_ERR_OK
        ];

        $imageUploadValidator = new ImageUpload();
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

    public function testValidUpload()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
            'error' => \UPLOAD_ERR_OK
        ];

        $imageUploadValidator = new ImageUpload();
        $this->assertTrue($imageUploadValidator->isValid($uploadFileData));

        $validData = $imageUploadValidator->getStandardValue();
        $expectedData = $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'image/jpeg',
            'path' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
            'ext' => 'fig',
            'size' => 10,
            'image' => [
                'width' => 500,
                'height' => 625
            ]
        ];

        $this->assertSame($expectedData, $validData);
    }
}

namespace Lavibi\Popoya;

if (!function_exists('Lavibi\Popoya\is_uploaded_file')) {
    function is_uploaded_file($file)
    {
        if (file_exists($file)) {
            return true;
        }

        return false;
    }
}
