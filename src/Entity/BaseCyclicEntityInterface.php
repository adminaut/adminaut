<?php

namespace Adminaut\Entity;

/**
 * Interface BaseCyclicEntityInterface
 * @package Adminaut\Entity
 */
interface BaseCyclicEntityInterface extends BaseEntityInterface
{
    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param int $id
     */
    public function setParentId($id);
}
