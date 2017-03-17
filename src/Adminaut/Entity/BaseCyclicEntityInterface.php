<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * Interface BaseInterface
 * @package Adminaut\Entity
 */
interface BaseCyclicEntityInterface extends BaseInterface
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