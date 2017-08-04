<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class File
 *
 * @ORM\Entity
 * @ORM\Table(name="adminaut_file_manager")
 * @package Adminaut\Entity
 * @ORM\HasLifecycleCallbacks
 */
class File implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @ORM\Column(type="string")
     */
    protected $mimetype;

    /**
     * @ORM\Column(type="string")
     */
    protected $savepath;

    /**
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\FileKeyword", mappedBy="file")
     * @ORM\OrderBy({"id" = "ASC"})
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $keywords;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $value
     */
    public function setSize($value)
    {
        $this->size = $value;
    }

    /**
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * @param $value
     */
    public function setMimetype($value)
    {
        $this->mimetype = $value;
    }

    /**
     * @return string
     */
    public function getSavePath()
    {
        return $this->savepath;
    }

    /**
     * @param $value
     */
    public function setSavePath($value)
    {
        $this->savepath = $value;
    }

    /**
     * @return ArrayCollection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param $keywords
     */
    public function setKeywords(array $keywords)
    {
        $this->keywords->clear();
        foreach ($keywords as $keyword) {
            if ($keyword instanceof FileKeyword) {
                $this->keywords->add($keyword);
            }
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $value
     */
    public function setUrl($value)
    {
        $this->url = $value;
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        $a = explode('.', $this->getName());
        return end($a);
    }

    public function getFormattedSize()
    {
        $bytes = $this->getSize();
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } else if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } else if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' kB';
        } else {
            return $bytes . ' B';
        }
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
    public function populate($data = [])
    {
        $this->setName($data['name']);
        $this->setSize($data['size']);
        $this->setMimetype($data['mimetype']);
        $this->setActive($data['active']);
        $this->setSavePath($data['savepath']);
    }

    /**
     * isImageCached
     *
     * @param $source_image
     * @param $result_image
     * @return bool
     */
    private function isImageCached($source_image, $result_image)
    {
        if (file_exists('www_root/' . $result_image) and filemtime('www_root/' . $result_image) >= filemtime('www_root/' . $source_image)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->inserted = new \DateTime();
    }
}
