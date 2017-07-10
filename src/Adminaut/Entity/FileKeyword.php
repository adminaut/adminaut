<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class FileKeyword
 * @ORM\Entity
 * @ORM\Table(name="adminaut_file_manager_keyword")
 * @property int $id
 * @property int $fileid
 * @property string $value
 * @package Adminaut\Entity
 */
class FileKeyword
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Adminaut\Entity\File", inversedBy="keywords")
     * @ORM\JoinColumn(name="fileid", referencedColumnName="id")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $value
     */
    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * @param $fileid
     */
    public function setFileid($fileid)
    {
        $this->fileid = $fileid;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $value
     */
    public function setFile($value)
    {
        $this->file = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->setFileId($data['fileid']);
        $this->setValue($data['value']);
    }
}