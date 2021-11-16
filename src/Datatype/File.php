<?php

namespace Adminaut\Datatype;

use Zend\Form\Element;
use Zend\InputFilter\FileInput;
use Zend\Validator\File\UploadFile;

/**
 * Class File
 * @package Adminaut\Datatype
 */
class File extends Element\File
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    protected $attributes = [
        'type' => 'datatypeFile',
    ];

    /**
     * @var \Adminaut\Entity\File
     */
    protected $file;

    /**
     * @return \Adminaut\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Adminaut\Entity\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return \Adminaut\Entity\File
     */
    public function getFileObject()
    {
        return $this->file;
    }

    /**
     * @param $fileObject
     */
    public function setFileObject($fileObject)
    {
        $this->file = $fileObject;
    }

    /**
     * @return \Adminaut\Entity\File
     */
    public function getInsertValue()
    {
        return $this->getFile();
    }

    /**
     * @param mixed $value
     * @return Element
     */
    public function setValue($value)
    {
        if ($value instanceof \Adminaut\Entity\File) {
            $this->setFile($value);
            $this->value = $value->getName();
        } else {
            $this->value = $value;
            $this->setFile(null);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        if($this->file) {
            return '<i class="fa fa-fw ' . $this->file->getFontAwesomeFileIconClass() . '"></i> '
                . $this->value . ' <span class="small">(' . $this->file->getFormattedSize() . ')</span>';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getExportValue()
    {
        if($this->file) {
            return sprintf('%s (%s)', $this->file->getName(), $this->file->getFormattedSize());
        }

        return '';
    }

    /**
     * @param array|\Traversable $options
     * @return \Zend\Form\Element
     */
    public function setOptions($options)
    {
        return $this->datatypeSetOptions($options);
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type' => FileInput::class,
            'name' => $this->getName(),
            'required' => false,
            'validators' => [
                [
                    'name' => 'fileuploadfile',
                    'options' => [
                        'messageTemplates' => [
                            UploadFile::INI_SIZE => sprintf(_('The uploaded file exceeds the maximum file size. Maximum file size is %s.'), $this->getIniFileUploadMaxSize()),
//                            UploadFile::FORM_SIZE      => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was '
//                                . 'specified in the HTML form',
                        ],
                    ],
                    'break_chain_on_failure' => true,
                ],
            ],
        ];
    }

    protected function getIniFileUploadMaxSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            $post_max_size = $this->parseIniSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            $upload_max = $this->parseIniSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($max_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return sprintf("%s %s", round($bytes, 2), $units[$pow]);
    }

    private function parseIniSize(string $size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}