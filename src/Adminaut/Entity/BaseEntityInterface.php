<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * Interface BaseEntityInterface
 * @package Adminaut\Entity
 */
interface BaseEntityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return \DateTime
     */
    public function getInserted();

    /**
     * @param \DateTime $inserted
     */
    public function setInserted($inserted);

    /**
     * @return int
     */
    public function getInsertedBy();

    /**
     * @param int $insertedBy
     */
    public function setInsertedBy($insertedBy);

    /**
     * @return \DateTime
     */
    public function getUpdated();

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated);

    /**
     * @return int
     */
    public function getUpdatedBy();

    /**
     * @param int $updatedBy
     */
    public function setUpdatedBy($updatedBy);

    /**
     * @return int
     */
    public function getDeleted();

    /**
     * @param int $deleted
     */
    public function setDeleted($deleted);

    /**
     * @return int
     */
    public function getDeletedBy();

    /**
     * @param int $deletedBy
     */
    public function setDeletedBy($deletedBy);

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param boolean $active
     */
    public function setActive($active);
}