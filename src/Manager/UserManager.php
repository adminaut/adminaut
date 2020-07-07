<?php

namespace Adminaut\Manager;

use Adminaut\Authentication\Helper\PasswordHelper;
use Adminaut\Entity\UserEntity;
use Adminaut\Repository\UserRepository;
use Adminaut\Service\RecoveryKeyGenerator;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserManager
 * @package Adminaut\Manager
 */
class UserManager extends AManager
{

    /**
     * @return EntityRepository|UserRepository
     */
    public function getUserRepository()
    {
        return $this->entityManager->getRepository(UserEntity::class);
    }

    /**
     * @param UserEntity $user
     * @param array $data
     * @return UserEntity
     */
    private function populateData(UserEntity $user, array $data)
    {
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['active'])) {
            $user->setActive($data['active']);
        }
        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }
        if (isset($data['password']) && '' != trim($data['password'])) {
            $user->setPassword(PasswordHelper::hash($data['password']));
        }
        return $user;
    }

    //-------------------------------------------------------------------------

    /**
     * @param $email
     * @return UserEntity|null|object
     */
    public function findOneByEmail($email)
    {
        return $this->getUserRepository()->findOneByEmail($email);
    }

    /**
     * @param $id
     * @return UserEntity|null|object
     */
    public function findOneById($id)
    {
        return $this->getUserRepository()->findOneById($id);
    }

    /**
     * @param string $email
     * @param string $passwordRecoveryKey
     * @return UserEntity|null
     */
    public function findByEmailAndPasswordRecoveryKey(string $email, string $passwordRecoveryKey)
    {
        return $this->getUserRepository()->findOneByEmailAndPasswordRecoveryKey($email, $passwordRecoveryKey);
    }

    /**
     * @return UserEntity[]|array
     */
    public function findAll()
    {
        return $this->getUserRepository()->findAll();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->getUserRepository()->countAll();
    }

    /**
     * @param array $data
     * @param UserEntity|null $admin
     * @return UserEntity
     */
    public function create(array $data, UserEntity $admin = null)
    {
        $user = new UserEntity();

        $user = $this->populateData($user, $data);

        if ($admin instanceof UserEntity) {
            $user->setInsertedBy($admin->getId());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param array $data
     * @return UserEntity
     */
    public function createSuperUser(array $data)
    {
        $user = new UserEntity();

        $data['active'] = true;
        $data['role'] = 'admin';
        $user = $this->populateData($user, $data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param UserEntity $user
     * @param array $data
     * @param UserEntity|null $admin
     * @return UserEntity
     */
    public function update(UserEntity $user, array $data, UserEntity $admin = null)
    {
        $user = $this->populateData($user, $data);

        if ($admin instanceof UserEntity) {
            $user->setUpdatedBy($admin->getId());
        }

        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param UserEntity $user
     * @param UserEntity|null $admin
     * @return UserEntity
     */
    public function delete(UserEntity $user, UserEntity $admin = null)
    {
        $user->setDeleted(true);

        if ($admin instanceof UserEntity) {
            $user->setDeletedBy($admin->getId());
        }

        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param UserEntity $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setPasswordRecoveryKey(UserEntity $user)
    {
        $user->setPasswordRecoveryKey(RecoveryKeyGenerator::generate());
        $user->setPasswordRecoveryExpiresAt(new \DateTime('+15 minutes'));

        $this->entityManager->flush();
    }

    /**
     * @param UserEntity $user
     * @param string $password
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setPasswordUsingRecoveryKey(UserEntity $user, string $password)
    {
        $user->setPasswordRecoveryKey(null);
        $user->setPasswordRecoveryExpiresAt(null);
        $user->setPassword(PasswordHelper::hash($password));

        $this->entityManager->flush();
    }
}
