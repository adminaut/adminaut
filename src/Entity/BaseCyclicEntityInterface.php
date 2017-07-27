<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

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