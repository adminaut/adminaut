<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RoleEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\RoleRepository")
 * @ORM\Table(name="adminaut_role")
 * @ORM\HasLifecycleCallbacks()
 */
class RoleEntity implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(type="string", length=32, unique=true);
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\Resource", mappedBy="role")
     */
    protected $resources;

    /**
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\UserEntity", mappedBy="role")
     */
    protected $users;

    /**
     * Role constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->resources = new ArrayCollection();
        $this->users = new ArrayCollection();
        if ($name) {
            $this->name = $name;
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
        $this->name = $name;
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
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
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
