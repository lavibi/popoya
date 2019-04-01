<?php

namespace Lavibi\Popoya;

class Upload extends AbstractValidator
{
    const NO_LIMIT_SIZE = 0;
    const ALL_TYPE = [];

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

    /**
     * Error code : Unknown error
     */
    const NO_UPLOADED_FILE = 'no_uploaded_file';

    /**
     * Error code : Not valid type
     */
    const NOT_VALID_TYPE = 'not_valid_type';

    const LARGE_SIZE = 'large_size';

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
        self::NO_UPLOADED_FILE => 'No uploaded file.',
        self::NOT_VALID_TYPE => 'Uploaded file has not valid type.',
        self::LARGE_SIZE => 'Uploaded file was too large.'
    ];

    /**
     * Size limit upload file, in byte.
     *
     * @param int $size
     *
     * @return $this
     */
    public function hasMaxSize($size)
    {
        $this->options['maxsize'] = (int) $size;

        return $this;
    }

    /**
     * Set allow file type (image/gif, image/png ...)
     *
     * @param string $type
     *
     * @return $this
     */
    public function allowType($type)
    {
        $this->options['type'][] = (string) $type;

        return $this;
    }

    /**
     * Validate.
     *
     * @param array $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!$this->isValidUploadFile($value['tmp_name'], $value['error'])) {
            return false;
        }

        if (!$this->isValidSize($value['size'])) {
            return false;
        }

        $value['type'] = $this->getFileType($value['tmp_name']);

        if (!$this->isValidFileType($value['type'])) {
            return false;
        }

        $this->standardValue = [
            'name' => $value['name'],
            'type' => $value['type'],
            'path' => $value['tmp_name'],
            'ext' => $this->getFileExt($value['name']),
            'size' => $value['size']
        ];

        return true;
    }

    /**
     * Validate uploaded file.
     *
     * @param string $path
     * @param int $error
     *
     * @return boolean
     */
    protected function isValidUploadFile($path, $error)
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
            $this->setError($case[$error]);
            return false;
        }

        if (!is_uploaded_file($path)) {
            $this->setError(self::NO_UPLOADED_FILE);
            return false;
        }

        return true;
    }

    /**
     * Validate size of uploaded file.
     *
     * @param int $size
     *
     * @return boolean
     */
    protected function isValidSize($size)
    {
        if ($this->options['maxsize'] === 0) {
            return true;
        }

        if ($size > $this->options['maxsize']) {
            $this->setError(self::LARGE_SIZE);
            return false;
        }

        return true;
    }

    protected function isValidFileType($type)
    {
        // Allow all mines
        if ($this->options['type'] === []) {
            return true;
        }

        // Not valid mine
        if (!in_array($type, $this->options['type'])) {
            $this->setError(self::NOT_VALID_TYPE);
            return false;
        }

        return true;
    }

    /**
     * Init.
     *
     * Set default options.
     */
    protected function init()
    {
        $this->defaultOptions = [
            'maxsize' => self::NO_LIMIT_SIZE,
            'type' => self::ALL_TYPE
        ];
    }

    /**
     * Get file type
     *
     * TODO: move to new project
     *
     * @param string $file File path
     * @return string
     */
    protected function getFileType($file)
    {
        $finfo = new \finfo();
        $type = $finfo->file($file, FILEINFO_MIME_TYPE);

        return $type;
    }

    /**
     * Get file extension
     *
     * TODO: move to new project
     *
     * @param string $file File name
     * @return string
     *
     *
     */
    protected function getFileExt($file)
    {
        $part = explode('.', $file);

        if (count($part) === 1) {
            return '';
        }

        return strtolower(end($part));
    }
}
