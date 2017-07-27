<?php

namespace Adminaut\Form\Element;

use Adminaut\Manager\FileManager;
use Zend\Form\Element\File as ZendFile;

/**
 * Class File
 * @package Adminaut\Form\Element
 */
class File extends ZendFile
{
    /**
     * @var \Adminaut\Entity\File
     */
    protected $fileObject;

    /**
     * @return \Adminaut\Entity\File
     */
    public function getFileObject()
    {
        return $this->fileObject;
    }

    /**
     * @param \Adminaut\Entity\File $fileObject
     */
    public function setFileObject($fileObject)
    {
        $this->fileObject = $fileObject;
    }

    /**
     * @return \Adminaut\Entity\File
     */
    public function getInsertValue()
    {
        return $this->getFileObject();
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        if ($value instanceof \Adminaut\Entity\File) {
            $this->setFileObject($value);
            $this->value = $value->getName();
        } else {
            $this->value = $value;
        }

        return $this;
    }

    public function getListedValue()
    {
        $fm = FileManager::getInstance();
    }
}
