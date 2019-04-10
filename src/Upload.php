<?php

namespace Lavibi\Popoya;

use Psr\Http\Message\UploadedFileInterface;

class Upload extends AbstractValidator
{
    /**
     * Error code : PHP upload error code.
     */
    const PHP_INI_SIZE = 'ini_size';
    const PHP_FORM_SIZE = 'form_size';
    const PHP_PARTIAL = 'partial';
    const PHP_NO_FILE = 'no_file';
    const PHP_NO_TMP_DIR = 'no_tmp_dir';
    const PHP_CANT_WRITE = 'cant_write';
    const PHP_EXTENSION = 'err_extension';
    const UNKNOWN_PHP_ERROR = 'unknown_php_error';

    /**
     * Error code : Unknown error
     */
    const NO_UPLOADED_FILE = 'no_uploaded_file';

    /**
     * Error code : Not valid type
     */
    const NOT_VALID_TYPE = 'not_valid_type';


    const NOT_UPLOAD_DATA = 'not_upload_data';

    /**
     * @var array|UploadedFileInterface|mixed
     */
    protected $value;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::PHP_INI_SIZE => 'Uploaded file exceeds the defined PHP INI size.',
        self::PHP_FORM_SIZE => 'Uploaded file exceeds the defined form size.',
        self::PHP_PARTIAL => 'Uploaded file was only partially uploaded.',
        self::PHP_NO_FILE => 'Uploaded file was not uploaded.',
        self::PHP_NO_TMP_DIR => 'Missing a temporary folder.',
        self::PHP_CANT_WRITE => 'Failed to write uploaded file to disk.',
        self::PHP_EXTENSION => 'Uploaded file was stopped by extension.',
        self::UNKNOWN_PHP_ERROR => 'Unknown PHP error.',
        self::NO_UPLOADED_FILE => 'No uploaded file.',
//        self::NOT_VALID_TYPE => 'Uploaded file has not valid type.',
        self::NOT_UPLOAD_DATA => 'Value is not valid upload data.'
    ];

    /**
     * Set allow file type (image/gif, image/png ...)
     *
     * @param string $type
     *
     * @return $this
     */
//    public function allowType($type)
//    {
//        $this->options['type'][] = (string) $type;
//
//        return $this;
//    }

    /**
     * Validate.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if ($value instanceof UploadedFileInterface) {
            return $this->isValidPSR7Upload();
        }

        if (is_array($value) && isset($value['tmp_name']) && isset($value['error'])) {
            return $this->isValidUploadFile();
        }

        $this->setError(static::NOT_UPLOAD_DATA);
        return false;
    }

    /**
     * Validate uploaded file.
     *
     * @return boolean
     */
    protected function isValidUploadFile()
    {
        $error = $this->value['error'];
        $path = $this->value['tmp_name'];

        if ($code = $this->isErrorUpload($error)) {
            $this->setError($code);
            return false;
        }

        if (!is_uploaded_file($path)) {
            $this->setError(static::NO_UPLOADED_FILE);
            return false;
        }

        $this->standardValue = $this->value;
        return true;
    }

    protected function isValidPSR7Upload()
    {
        $error = $this->value->getError();

        if ($code = $this->isErrorUpload($error)) {
            $this->setError($code);
            return false;
        }

        $this->standardValue = $this->value;
        return true;
    }

    protected function isErrorUpload($error)
    {
        $case = [
            UPLOAD_ERR_INI_SIZE => self::PHP_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE => self::PHP_FORM_SIZE,
            UPLOAD_ERR_PARTIAL => self::PHP_PARTIAL,
            UPLOAD_ERR_NO_FILE => self::PHP_NO_FILE,
            UPLOAD_ERR_NO_TMP_DIR => self::PHP_NO_TMP_DIR,
            UPLOAD_ERR_CANT_WRITE => self::PHP_CANT_WRITE,
            UPLOAD_ERR_EXTENSION => self::PHP_EXTENSION
        ];

        if (isset($case[$error])) {
            return $case[$error];
        }

        if ($error !== 0) {
            return static::UNKNOWN_PHP_ERROR;
        }

        return false;
    }
}
