<?php

namespace Adminaut\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\Form\Annotation\Options;

/**
 * Trait AdminautEntityTrait
 * @package Adminaut\Entity
 * @ORM\HasLifecycleCallbacks()
 */
trait AdminautEntityTrait
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
     * @ORM\Column(name="inserted", type="datetime", options={"default":0});
     * @Annotation\Exclude();
     * @var \DateTime
     */
    protected $inserted;

    /**
     * @ORM\Column(name="inserted_by", type="integer", options={"default":0});
     * @Annotation\Exclude();
     * @var int
     */
    protected $insertedBy = 0;

    /**
     * @ORM\Column(name="updated", type="datetime", options={"default":0});
     * @Annotation\Exclude();
     * @var \DateTime
     */
    protected $updated;

    /**
     * @ORM\Column(name="updated_by", type="integer", options={"default":0});
     * @Annotation\Exclude();
     * @var int
     */
    protected $updatedBy = 0;

    /**
     * @ORM\Column(name="deleted", type="boolean", options={"default":false});
     * @Annotation\Exclude();
     * @var bool
     */
    protected $deleted = false;

    /**
     * @ORM\Column(name="deleted_by", type="integer");
     * @Annotation\Exclude();
     * @var int
     */
    protected $deletedBy = 0;

    /**
     * @ORM\Column(name="active", type="boolean");
     * @Annotation\Options({"label":"Status", "listed":false, "checkbox_label":"Active", "listed_checked_value":"Active", "listed_unchecked_value":"Inactive", "listed":true});
     * @Annotation\Type("Adminaut\Datatype\Checkbox");
     * @var boolean
     */
    protected $active = true;

    /**
     * @var string
     */
    protected $primaryField;

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
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @return bool
     * @deprecated use isDeleted()
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
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
     * Alias for isActive
     * @return bool
     */
    public function getActive()
    {
        return $this->isActive();
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
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->$name = $value;
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function _adminautPrePersist()
    {
        $this->inserted = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function _adminautPreUpdate()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return mixed|string
     */
    public function getPrimaryField()
    {
        if (!empty($this->primaryField)) {
            return $this->primaryField;
        }

        $pAR = new AnnotationReader();
        $pRO = new \ReflectionObject($this);

        foreach ($pRO->getProperties() as $property) {
            foreach ($pAR->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof Options) {
                    $_options = $annotation->getOptions();

                    if (isset($_options['primary']) && $_options['primary'] === true) {
                        $this->primaryField = $property->getName();
                    }
                }
            }
        }

        if (empty($this->primaryField)) {
            $this->primaryField = 'id';
        }

        return $this->primaryField;
    }

    /**
     * @return mixed|null
     */
    public function getPrimaryFieldValue()
    {
        $primaryField = $this->getPrimaryField();

        if (property_exists($this, $primaryField)) {
            if (method_exists($this, 'get' . $primaryField)) {
                return $this->{'get' . $primaryField}();
            }

            return $this->$primaryField;
        }

        return null;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }
}
