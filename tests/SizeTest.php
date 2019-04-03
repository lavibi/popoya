<?php

namespace Lavibi\PopoyaTest;

use PHPUnit\Framework\TestCase;
use Lavibi\Popoya\Size;
use DateTime;
use SplFileInfo;

class SizeTest extends TestCase
{
    public function testNotValidSize()
    {
        $sizeValidator = new Size();

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid('a'));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid(6.7));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid('4'));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid([]));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid(['s' => 8]));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid(['size' => '9']));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());

        $sizeValidator->reset();
        $this->assertFalse($sizeValidator->isValid(new DateTime()));
        $this->assertSame('Give value is not valid size.', $sizeValidator->getMessage());
        $this->assertSame('not_valid_size', $sizeValidator->getMessageCode());
    }

    public function testValidWithIntValue()
    {
        $sizeValidator = new Size();

        $sizeValidator->reset();
        $this->assertTrue($sizeValidator->isValid(4));

        $sizeValidator->reset();
        $this->assertTrue($sizeValidator->isValid(0));
    }

    public function testValidUploadFile()
    {
        $sizeValidator = new Size();

        $sizeValidator->reset();
        $this->assertTrue($sizeValidator->isValid(new UploadFile('0', 23)));
    }

    public function testValidArray()
    {
        $sizeValidator = new Size();

        $sizeValidator->reset();
        $this->assertTrue($sizeValidator->isValid(['size' => 9]));
    }

    public function testValidSplFileInfo()
    {
        $file = new SplFileInfo('./tests/file/phone.svg');
        $sizeValidator = new Size();

        $sizeValidator->reset();
        $this->assertTrue($sizeValidator->isValid($file));
    }

    public function testLargerThanMax()
    {
        $sizeValidator = new Size();
        $sizeValidator->hasMaxSize(599);

        $this->assertFalse($sizeValidator->isValid(['size' => 999]));
        $this->assertSame('Size of file is too large.', $sizeValidator->getMessage());
        $this->assertSame('greater_than_max', $sizeValidator->getMessageCode());

        $this->assertFalse($sizeValidator->isValid(['size' => 600]));
        $this->assertSame('Size of file is too large.', $sizeValidator->getMessage());
        $this->assertSame('greater_than_max', $sizeValidator->getMessageCode());

        $this->assertTrue($sizeValidator->isValid(['size' => 500]));
        $this->assertTrue($sizeValidator->isValid(['size' => 599]));
    }

    public function testSmallerThanMin()
    {
        $sizeValidator = new Size();
        $sizeValidator->hasMinSize(599);

        $this->assertFalse($sizeValidator->isValid(['size' => 400]));
        $this->assertSame('Size of file is too small.', $sizeValidator->getMessage());
        $this->assertSame('smaller_than_min', $sizeValidator->getMessageCode());

        $this->assertFalse($sizeValidator->isValid(['size' => 588]));
        $this->assertSame('Size of file is too small.', $sizeValidator->getMessage());
        $this->assertSame('smaller_than_min', $sizeValidator->getMessageCode());

        $this->assertTrue($sizeValidator->isValid(['size' => 600]));
        $this->assertTrue($sizeValidator->isValid(['size' => 599]));
    }

    public function testBothMinMax()
    {
        $sizeValidator = new Size();
        $sizeValidator->hasMinSize(599);
        $sizeValidator->hasMaxSize(900);

        $this->assertFalse($sizeValidator->isValid(['size' => 400]));
        $this->assertSame('Size of file is too small.', $sizeValidator->getMessage());
        $this->assertSame('smaller_than_min', $sizeValidator->getMessageCode());

        $this->assertFalse($sizeValidator->isValid(['size' => 901]));
        $this->assertSame('Size of file is too large.', $sizeValidator->getMessage());
        $this->assertSame('greater_than_max', $sizeValidator->getMessageCode());

        $this->assertTrue($sizeValidator->isValid(['size' => 600]));
        $this->assertTrue($sizeValidator->isValid(['size' => 599]));
        $this->assertTrue($sizeValidator->isValid(['size' => 900]));
    }
}
