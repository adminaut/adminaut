<?php

namespace Adminaut\Service;

use Doctrine\ORM\EntityManager;
use Adminaut\Entity\Role as RoleEntity;
use Adminaut\Entity\Resource as ResourceEntity;
use Adminaut\Form\RolePermission as RolePermission;
use Adminaut\Mapper\Resource as ResourceMapper;
use Adminaut\Mapper\Role as RoleMapper;
use Adminaut\Mapper\User as UserMapper;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class AccessControl
 * @package Adminaut\Service
 */
class _AccessControl
{
    const NONE = 0;
    const READ = 1;
    const WRITE = 2;

    /**
     * @var Acl
     */
    private $acl;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var RoleMapper
     */
    protected $roleMapper;

    /**
     * @var ResourceMapper
     */
    protected $resourceMapper;

    /**
     * @var array
     */
    protected $modules;

    /**
     * @var array
     */
    private $allowPermission = [0, 1, 2];

    /**
     * AccessControl constructor.
     * @param $config
     * @param $entityManager
     * @param $userMapper
     * @param $roleMapper
     * @param $resourceMapper
     */
    public function __construct($config, $entityManager, $userMapper, $roleMapper, $resourceMapper)
    {
        $this->setConfig($config);
        $this->setEntityManager($entityManager);
        $this->setUserMapper($userMapper);
        $this->setRoleMapper($roleMapper);
        $this->setResourceMapper($resourceMapper);

        $this->modules = $this->getConfig()['mfcc_admin']['modules'];
        $this->acl = new Acl();

        foreach ($this->getRoleMapper()->getAll() as $index => $role) {
            /* @var $role RoleEntity */
            $this->acl->addRole(new Role($role->getName()));
        }

        foreach ($this->modules as $index => $module) {
            $this->acl->addResource(new GenericResource($module['module_name']));
        }
        $this->acl->addResource(new GenericResource('Users'));
        $this->acl->addResource(new GenericResource('Roles'));

        foreach ($this->getResourceMapper()->getAll() as $index => $resource) {
            /* @var $resource ResourceEntity */
            $this->acl->allow($resource->getRole()->getName(), $resource->getResource(), $resource->getPermission());
            if ($resource->getPermission() == self::WRITE) {
                $this->acl->allow($resource->getRole()->getName(), $resource->getResource(), self::READ);
            }
        }
    }

    /**
     * @return Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param RoleEntity $role
     * @return array
     */
    public function getPermissionsToArray(RoleEntity $role)
    {
        $permission = [];
        foreach ($role->getResources() as $resource) {
            /* @var $resource ResourceEntity */
            $permission[$resource->getResource()] = $resource->getPermission();
        }
        return $permission;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createRole(array $data)
    {
        $entity = new RoleEntity($data['name']);
        return $this->getRoleMapper()->insert($entity);
    }

    /**
     * @param RoleEntity $entity
     * @param array $data
     * @return mixed
     */
    public function updateRole(RoleEntity $entity, array $data)
    {
        $entity = $this->populateRoleData($entity, $data);
        return $this->getRoleMapper()->update($entity);
    }

    /**
     * @param RoleEntity $entity
     * @return mixed
     */
    public function deleteRole(RoleEntity $entity)
    {
        return $this->getRoleMapper()->delete($entity);
    }

    /**
     * @param RoleEntity $entity
     * @param array $data
     * @return RoleEntity
     */
    protected function populateRoleData(RoleEntity $entity, array $data)
    {
        if ($data['name']) {
            $entity->setName($data['name']);
        }
        return $entity;
    }

    /**
     * @param RoleEntity $role
     * @param array $data
     * @return RoleEntity
     */
    public function updateRolePermissions(RoleEntity $role, array $data)
    {
        unset($data['name']);
        unset($data['submit']);

        $EntityManager = $this->getEntityManager();
        foreach ($data as $moduleName => $permission) {
            $permission = intval($permission);
            if (!in_array($permission, $this->allowPermission)) {
                throw new \InvalidArgumentException('Not allowed permission');
            }
            $createNewPermission = true;
            foreach ($role->getResources() as $resource) {
                /* @var $resource \Adminaut\Entity\Resource */
                if ($resource->getResource() == $moduleName) {
                    $resource->setPermission($permission);
                    $EntityManager->persist($resource);
                    $createNewPermission = false;
                    break;
                }
            }

            if ($createNewPermission) {
                $newResource = new ResourceEntity();
                $newResource->setRole($role);
                $newResource->setResource($moduleName);
                $newResource->setPermission($permission);
                $EntityManager->persist($newResource);
            }
        }
        $EntityManager->flush();
        return $role;
    }

    /**
     * @param RoleEntity $role
     * @return \Adminaut\Form\Role
     */
    public function getRoleForm(RoleEntity $role)
    {
        $resources = $this->getResourceMapper()->getAllByRole($role);
        $form = new \Adminaut\Form\Role("update", $this->modules, $resources);
        return $form;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return UserMapper
     */
    public function getUserMapper(): UserMapper
    {
        return $this->userMapper;
    }

    /**
     * @param UserMapper $userMapper
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @return RoleMapper
     */
    public function getRoleMapper(): RoleMapper
    {
        return $this->roleMapper;
    }

    /**
     * @param RoleMapper $roleMapper
     */
    public function setRoleMapper(RoleMapper $roleMapper)
    {
        $this->roleMapper = $roleMapper;
    }

    /**
     * @return ResourceMapper
     */
    public function getResourceMapper(): ResourceMapper
    {
        return $this->resourceMapper;
    }

    /**
     * @param ResourceMapper $resourceMapper
     */
    public function setResourceMapper(ResourceMapper $resourceMapper)
    {
        $this->resourceMapper = $resourceMapper;
    }
}