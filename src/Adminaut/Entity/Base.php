<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * Class Base
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @package Adminaut\Entity
 */
class Base implements BaseInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer");
     * @ORM\GeneratedValue(strategy="AUTO");
     * @Annotation\Exclude();
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="inserted", type="datetime", options={"default":"CURRENT_TIMESTAMP"});
     * @Annotation\Exclude();
     * @var \DateTime
     */
    protected $inserted;

    /**
     * @ORM\Column(name="inserted_by", type="integer");
     * @Annotation\Exclude();
     * @var int
     */
    protected $insertedBy;

    /**
     * @ORM\Column(name="updated", type="datetime", options={"default":"CURRENT_TIMESTAMP"});
     * @Annotation\Exclude();
     * @var \DateTime
     */
    protected $updated;

    /**
     * @ORM\Column(name="updated_by", type="integer");
     * @Annotation\Exclude();
     * @var int
     */
    protected $updatedBy;

    /**
     * @ORM\Column(name="deleted", type="integer");
     * @Annotation\Exclude();
     * @var int
     */
    protected $deleted = 0;

    /**
     * @ORM\Column(name="deleted_by", type="integer");
     * @Annotation\Exclude();
     * @var int
     */
    protected $deletedBy = 0;

    /**
     * @ORM\Column(name="active", type="boolean");
     * @Annotation\Attributes({"data-toggle":"toggle", "data-onstyle":"success", "data-offstyle":"danger", "data-on":"Active", "data-off":"Inactive"});
     * @Annotation\Options({"label":"Status", "listed":true, "listed_checked_value":"Active", "listed_unchecked_value":"Inactive"});
     * @Annotation\Type("Adminaut\Form\Element\Checkbox");
     * @var boolean
     */
    protected $active = true;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getInserted()
    {
        return $this->inserted;
    }

    /**
     * @param \DateTime $inserted
     */
    public function setInserted($inserted)
    {
        $this->inserted = $inserted;
    }

    /**
     * @return int
     */
    public function getInsertedBy()
    {
        return $this->insertedBy;
    }

    /**
     * @param int $insertedBy
     */
    public function setInsertedBy($insertedBy)
    {
        $this->insertedBy = $insertedBy;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param int $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param int $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return int
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * @param int $deletedBy
     */
    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
       return $this->$name;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->inserted = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist()
    {

    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PostUpdate
     */
    public function postUpdate()
    {

    }
}