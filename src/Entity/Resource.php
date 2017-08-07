<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Resource
 * @package Adminaut\Entity
 * @ORM\Entity()
 * @ORM\Table(name="adminaut_resource")
 * @ORM\HasLifecycleCallbacks()
 */
class Resource implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Adminaut\Entity\RoleEntity", inversedBy="resources")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    /**
     * @ORM\Column(type="string", length=32);
     * @var string
     */
    protected $resource;

    /**
     * @ORM\Column(type="smallint");
     * @var int
     */
    protected $permission;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return int
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param int $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'role' => $this->role->getId(),
            'resource' => $this->resource,
            'permission' => $this->permission,
        ];
    }
}