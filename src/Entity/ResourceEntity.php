<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ResourceEntity
 * @package Adminaut\Entity
 * @ORM\Entity()
 * @ORM\Table(name="adminaut_resources")
 * @ORM\HasLifecycleCallbacks()
 */
class ResourceEntity implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(type="integer", name="role_id")
     * @var int
     */
    protected $roleId;

    /**
     * @ORM\ManyToOne(targetEntity="Adminaut\Entity\RoleEntity", inversedBy="resources")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * @var RoleEntity
     */
    protected $role;

    /**
     * @ORM\Column(type="string", name="resource");
     * @var string
     */
    protected $resource = '';

    /**
     * @ORM\Column(type="smallint", name="permission", options={"default":0});
     * @var int
     */
    protected $permission = 0;

    /**
     * @return RoleEntity
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param RoleEntity $role
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
