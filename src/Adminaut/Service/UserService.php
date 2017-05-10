<?php

namespace Adminaut\Service;

use Adminaut\Entity\Role as RoleEntity;
use Adminaut\Entity\UserEntity;
use Adminaut\EventManager\EventProvider;
use Adminaut\Mapper\RoleMapper as RoleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\UserOptions;

use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class UserService
 * @package Adminaut\Service
 */
class UserService extends EventProvider
{
    /**
     * @var AccessControlService
     */
    protected $accessControlService;

    /**
     * @var RoleMapper
     */
    protected $roleMapper;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var AuthenticationService
     */
    protected $userAuthService;

    /**
     * @var UserOptions
     */
    protected $userOptions;

    public function __construct($accessControl, $roleMapper, $userMapper, $userAuthService, $userOptions)
    {
        $this->setAccessControlService($accessControl);
        $this->setUserMapper($userMapper);
        $this->setRoleMapper($roleMapper);
        $this->setUserAuthService($userAuthService);
        $this->setUserOptions($userOptions);
    }

    /**
     * @param array $data
     * @param UserEntity $user
     * @return mixed
     */
    public function add(array $data, UserEntity $user)
    {
        $entity = new UserEntity();
        $entity->setInsertedBy($user->getId());
        $entity->setUpdatedBy($user->getId());
        $entity = $this->populateData($entity, $data);
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getUserOptions()->getPasswordCost());
        $entity->setPassword($bcrypt->create($data['password']));
        if ($this->getUserOptions()->isEnableUserStatus()) {
            $entity->setStatus($this->getUserOptions()->getDefaultUserStatus());
        }
        return $this->getUserMapper()->insert($entity);
    }

    /**
     * @param UserEntity $entity
     * @param array $data
     * @param UserEntity $user
     * @return mixed
     */
    public function update(UserEntity $entity, array $data, UserEntity $user)
    {
        $entity->setUpdatedBy($user->getId());
        $entity = $this->populateData($entity, $data);
        if ($data['password']) {
            $bcrypt = new Bcrypt;
            $bcrypt->setCost($this->getUserOptions()->getPasswordCost());
            $entity->setPassword($bcrypt->create($data['password']));
        }
        return $this->getUserMapper()->update($entity);
    }

    /**
     * @param UserEntity $entity
     * @param UserEntity $user
     * @return mixed
     */
    public function delete(UserEntity $entity, UserEntity $user)
    {
        $entity->setDeleted(1);
        $entity->setDeletedBy($user->getId());
        return $this->getUserMapper()->update($entity);
    }

    /**
     * @return bool
     */
    public function checkSuperuser()
    {
        $user = $this->getUserMapper()->findFirst();
        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createSuperuser(array $data)
    {
        $entity = new UserEntity();
        $entity->setInsertedBy(1);
        $entity->setUpdatedBy(1);
        $data['active'] = true;
        $data['role'] = 'admin';
        $entity = $this->populateData($entity, $data);
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getUserOptions()->getPasswordCost());
        $entity->setPassword($bcrypt->create($data['password']));
        if ($this->getUserOptions()->isEnableUserStatus()) {
            $entity->setStatus($this->getUserOptions()->getDefaultUserStatus());
        }
        return $this->getUserMapper()->insert($entity);
    }

    /**
     * @param UserEntity $entity
     * @param array $data
     * @return UserEntity
     */
    protected function populateData(UserEntity $entity, array $data)
    {
        if ($data['name']) {
            $entity->setName($data['name']);
        }
        if ($data['email']) {
            $entity->setEmail($data['email']);
        }
        if ($data['active']) {
            $entity->setActive($data['active']);
        }
        if ($data['role']) {
            $entity->setRole($data['role']);
        }
        return $entity;
    }

    /**
     * @return AccessControlService
     */
    public function getAccessControlService()
    {
        return $this->accessControlService;
    }

    /**
     * @param AccessControlService $accessControlService
     */
    public function setAccessControlService(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * @return RoleMapper
     */
    public function getRoleMapper()
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
     * @return UserMapper
     */
    public function getUserMapper()
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
     * @return AuthenticationService
     */
    public function getUserAuthService()
    {
        return $this->userAuthService;
    }

    /**
     * @param AuthenticationService $userAuthService
     */
    public function setUserAuthService(AuthenticationService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    /**
     * @return UserOptions
     */
    public function getUserOptions()
    {
        return $this->userOptions;
    }

    /**
     * @param UserOptions $userOptions
     */
    public function setUserOptions(UserOptions $userOptions)
    {
        $this->userOptions = $userOptions;
    }
}