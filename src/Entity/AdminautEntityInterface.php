<?php

namespace Adminaut\Entity;

/**
 * Interface AdminautEntityInterface
 * @package Adminaut\Entity
 */
interface AdminautEntityInterface
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
     * @return bool
     */
    public function isDeleted();

    /**
     * @return bool
     * @deprecated use isDeleted()
     */
    public function getDeleted();

    /**
     * @param bool $deleted
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
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     */
    public function setActive($active);
}
