<?php
/**
 * Created by PhpStorm.
 * User: Josef
 * Date: 18.8.2016
 * Time: 14:37
 */

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Zend\Form\Annotation;

/**
 * Class BaseCyclicEntity
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @package Adminaut\Entity
 */
class BaseCyclicEntity extends Base
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Annotation\Type("Zend\Form\Element\Hidden");
     */
    protected $parentId;

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
}