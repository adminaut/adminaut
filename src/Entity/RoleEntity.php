<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RoleEntity
 * @package Adminaut\Entity
 * @ORM\Entity()
 * @ORM\Table(name="adminaut_roles")
 * @ORM\HasLifecycleCallbacks()
 */
class RoleEntity implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(type="string", name="name", unique=true);
     * @var string
     */
    protected $name = '';

    /**
     * Inverse side.
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\ResourceEntity", mappedBy="role")
     * @var ArrayCollection
     */
    protected $resources;

    /**
     * RoleEntity constructor.
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->resources = new ArrayCollection();
        if ($name) {
            $this->setName($name);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * @return ArrayCollection
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param ArrayCollection $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
