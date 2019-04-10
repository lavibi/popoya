<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Validator;

class ValidatorTest extends TestCase
{
    public function testValidatorRequireAndOptionalParam()
    {
        // test 1
        $validator = new Validator();

        $validator->isRequired('avatar');
        $validator->isRequired('name');
        $validator->isOptional('email');

        $this->assertFalse($validator->isValid([
            'avatar' => 3,
            'email' => 4
        ]));

        // test 2
        $validator = new Validator();

        $validator->isOptional('avatar');
        $validator->isRequired('name');
        $validator->isOptional('email');

        $this->assertTrue($validator->isValid([
            'name' => 3,
            'email' => 4
        ]));

        // test 3
        $validator = new Validator();

        $validator->isOptional('avatar');
        $validator->isOptional('name');
        $validator->isOptional('email');

        $this->assertTrue($validator->isValid([
            'name' => 3,
            'email' => 4
        ]));

        // test 4
        $validator = new Validator();

        $validator->isOptional('avatar');
        $validator->isOptional('name');
        $validator->isOptional('email');

        $this->assertTrue($validator->isValid([]));
    }

    public function testValid()
    {
        $validator = new Validator();

        $validator->isOptional('avatar')->isUpload()->isImage();
        $validator->isOptional('name')->sameAs('A');

        $this->assertTrue($validator->isValid([
            'name' => 'A'
        ]));

        $this->assertTrue($validator->isValid([
            'avatar' => [
                'name' => 'abc.fig',
                'type' => 'type/type',
                'size' => 10,
                'tmp_name' => './tests/file/gerald-dicen-1463556-unsplash.jpg',
                'error' => UPLOAD_ERR_OK
            ],
            'name' => 'A'
        ]));
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