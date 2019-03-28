<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\UploadFile;

class UploadFileTest extends TestCase
{
    public function testPhpFailedUpload()
    {
        $data = $this->getPhpFailedUploadData();

        $uploadFileValidator = new UploadFile();

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_INI_SIZE]));
        $this->assertSame('Uploaded file exceeds the defined PHP INI size.', $uploadFileValidator->getMessage());
        $this->assertSame('ini_size', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_FORM_SIZE]));
        $this->assertSame('Uploaded file exceeds the defined form size.', $uploadFileValidator->getMessage());
        $this->assertSame('form_size', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_PARTIAL]));
        $this->assertSame('Uploaded file was only partially uploaded.', $uploadFileValidator->getMessage());
        $this->assertSame('partial', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_NO_FILE]));
        $this->assertSame('Uploaded file was not uploaded.', $uploadFileValidator->getMessage());
        $this->assertSame('no_file', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_NO_TMP_DIR]));
        $this->assertSame('Missing a temporary folder.', $uploadFileValidator->getMessage());
        $this->assertSame('no_tmp_dir', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_CANT_WRITE]));
        $this->assertSame('Failed to write uploaded file to disk.', $uploadFileValidator->getMessage());
        $this->assertSame('cant_write', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($data[\UPLOAD_ERR_EXTENSION]));
        $this->assertSame('Uploaded file was stopped by extension.', $uploadFileValidator->getMessage());
        $this->assertSame('err_extension', $uploadFileValidator->getMessageCode());
    }

    public function testNoUploadFile()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone1.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $uploadFileValidator = new UploadFile();

        $uploadFileValidator->reset();
        $this->assertFalse($uploadFileValidator->isValid($uploadFileData));
        $this->assertSame('No uploaded file.', $uploadFileValidator->getMessage());
        $this->assertSame('no_uploaded_file', $uploadFileValidator->getMessageCode());
    }

    public function testUploadWithFileType()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $uploadFileValidator = new UploadFile();
        $this->assertTrue($uploadFileValidator->isValid($uploadFileData));

        $uploadFileValidator->reset();
        $uploadFileValidator->allowType('image/png');
        $this->assertFalse($uploadFileValidator->isValid($uploadFileData));
        $this->assertSame('Uploaded file has not valid type.', $uploadFileValidator->getMessage());
        $this->assertSame('not_valid_type', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $uploadFileValidator->allowType('image/svg');
        $this->assertTrue($uploadFileValidator->isValid($uploadFileData));
    }

    public function testUploadWithFileSize()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $uploadFileValidator = new UploadFile();
        $this->assertTrue($uploadFileValidator->isValid($uploadFileData));

        $uploadFileValidator->reset();
        $uploadFileValidator->hasMaxSize(9);
        $this->assertFalse($uploadFileValidator->isValid($uploadFileData));
        $this->assertSame('Uploaded file was too large.', $uploadFileValidator->getMessage());
        $this->assertSame('large_size', $uploadFileValidator->getMessageCode());

        $uploadFileValidator->reset();
        $uploadFileValidator->hasMaxSize(11);
        $this->assertTrue($uploadFileValidator->isValid($uploadFileData));
    }

    public function testValidUpload()
    {
        $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'type/type',
            'size' => 10,
            'tmp_name' => './tests/file/phone.svg',
            'error' => \UPLOAD_ERR_OK
        ];

        $uploadFileValidator = new UploadFile();
        $this->assertTrue($uploadFileValidator->isValid($uploadFileData));

        $validData = $uploadFileValidator->getStandardValue();
        $expectedData = $uploadFileData = [
            'name' => 'abc.fig',
            'type' => 'image/svg',
            'path' => './tests/file/phone.svg',
            'ext' => 'fig',
            'size' => 10
        ];

        $this->assertSame($expectedData, $validData);
    }

    protected function getPhpFailedUploadData()
    {
        return [
            \UPLOAD_ERR_INI_SIZE => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_INI_SIZE
            ],
            \UPLOAD_ERR_FORM_SIZE => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_FORM_SIZE
            ],
            \UPLOAD_ERR_PARTIAL => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_PARTIAL
            ],
            \UPLOAD_ERR_NO_FILE => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_NO_FILE
            ],
            \UPLOAD_ERR_NO_TMP_DIR => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_NO_TMP_DIR
            ],
            \UPLOAD_ERR_CANT_WRITE => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_CANT_WRITE
            ],
            \UPLOAD_ERR_EXTENSION => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => '/temp/uploaded_file',
                'error' => \UPLOAD_ERR_EXTENSION
            ]
        ];
    }
}

namespace Lavibi\Popoya;

function is_uploaded_file($file)
{
    if (file_exists($file)) {
        return true;
    }

    return false;
}
