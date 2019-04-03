<?php

namespace Lavibi\PopoyaTest;

use Psr\Http\Message\UploadedFileInterface;

class UploadFile implements UploadedFileInterface
{
    protected $error;
    protected $size;
    protected $file;

    public function __construct($error = 0, $size = 0, $file = '')
    {
        $this->error = $error;
        $this->size = (int) $size;
        $this->file = $file;
    }

    public function getClientFilename()
    {
        // TODO: Implement getClientFilename() method.
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getStream()
    {
        return fopen($this->file, 'r');
    }

    public function moveTo($targetPath)
    {
        // TODO: Implement moveTo() method.
    }

    public function getClientMediaType()
    {
        // TODO: Implement getClientMediaType() method.
    }
}
