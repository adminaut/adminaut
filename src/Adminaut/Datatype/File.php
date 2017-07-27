<?php
namespace Adminaut\Datatype;

use Adminaut\Manager\FileManager;
use Zend\Form\Element;

/**
 * Class File
 * @package Adminaut\Datatype
 */
class File extends Element\File
{
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
     * @deprecated
     * @return \Adminaut\Entity\File
     */
    public function getFileObject()
    {
        return $this->file;
    }

    /**
     * @deprecated
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
        }
        return $this;
    }

    /**
     *
     */
    public function getListedValue()
    {
        // TODO[petrm] ?????
        $fm = FileManager::getInstance();
    }
}