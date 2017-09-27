<?php

namespace Adminaut\Service;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\BaseEntityInterface;
use Adminaut\Entity\UserEntity;
use Adminaut\EventManager\EventProvider;
use Adminaut\Form\Element;
use Adminaut\Mapper\RoleMapper as RoleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Authentication\Service\AuthenticationService;
use Zend\Form\Form;

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
     * UserService constructor.
     * @param $accessControl
     * @param $roleMapper
     * @param $userMapper
     * @param $userAuthService
     */
    public function __construct($accessControl, $roleMapper, $userMapper, $userAuthService)
    {
        $this->setAccessControlService($accessControl);
        $this->setUserMapper($userMapper);
        $this->setRoleMapper($roleMapper);
        $this->setUserAuthService($userAuthService);
    }

    /**
     * @param Form $form
     * @param UserEntity $user
     * @return mixed
     */
    public function add(Form $form, UserEntity $user)
    {
        $entity = new UserEntity();
        $entity->setInsertedBy($user->getId());
        $entity->setUpdatedBy($user->getId());
        $entity = $this->populateData($entity, $form);
        $data = $form['password'];
        $entity->setPassword(PasswordHelper::hash($data['password']));
        return $this->getUserMapper()->insert($entity);
    }

    /**
     * @param UserEntity $entity
     * @param array $data
     * @param UserEntity $user
     * @return mixed
     */
    public function update(UserEntity $entity, Form $form, UserEntity $user)
    {
        $entity->setUpdatedBy($user->getId());
        $entity = $this->populateData($entity, $form);
        $data = $form->getData();
        if ($data['password']) {
            $entity->setPassword(PasswordHelper::hash($data['password']));
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
        $entity = $this->populateDataFromArray($entity, $data);
        $entity->setPassword(PasswordHelper::hash($data['password']));
        return $this->getUserMapper()->insert($entity);
    }

    /**
     * @param UserEntity $entity
     * @param array $data
     * @return UserEntity
     */
    protected function populateDataFromArray(UserEntity $entity, array $data)
    {
        foreach ($data as $key => $value) {
            $entity->{$key} = $value;
        }

        return $entity;
    }



    /**
     * @param BaseEntityInterface $entity
     * @param Form $form
     * @return BaseEntityInterface
     */
    protected function populateData(UserEntity $entity, Form $form)
    {
        /* @var $element Element */
        foreach ($form->getElements() as $element) {
            $elementName = $element->getName();

            if (method_exists($element, 'getInsertValue')) {
                $entity->{$elementName} = $element->getInsertValue();
            } else {
                $entity->{$elementName} = $element->getValue();
            }
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
}
