<?php

namespace Adminaut\Mapper;

use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserFailedLoginEntity;
use Adminaut\Repository\UserFailedLoginRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserFailedLoginMapper
 * @package Adminaut\Mapper
 */
class UserFailedLoginMapper
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserFailedLoginMapper constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityRepository|UserFailedLoginRepository
     */
    private function getUserFailedLoginRepository()
    {
        return $this->entityManager->getRepository(UserFailedLoginEntity::class);
    }

    /**
     * @param int $userId
     * @return UserFailedLoginEntity[]|array
     */
    public function getAllByUserId($userId)
    {
        return $this->getUserFailedLoginRepository()->findByUserId($userId);
    }

    /**
     * @param UserEntity $userEntity
     * @return UserFailedLoginEntity[]|array
     */
    public function getAllByUser(UserEntity $userEntity)
    {
        return $this->getUserFailedLoginRepository()->findByUser($userEntity);
    }

    /**
     * @param Criteria $criteria
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllMatching(Criteria $criteria)
    {
        return $this->getUserFailedLoginRepository()->matching($criteria);
    }

    /**
     * @param UserFailedLoginEntity $userFailedLoginEntity
     * @return UserFailedLoginEntity
     */
    public function insert(UserFailedLoginEntity $userFailedLoginEntity)
    {
        $this->entityManager->persist($userFailedLoginEntity);
        $this->entityManager->flush();
        return $userFailedLoginEntity;
    }

    /**
     * @param UserEntity $userEntity
     */
    public function removeAllByUser(UserEntity $userEntity)
    {
        // do not remove, just mark deleted!
        foreach ($this->getAllByUser($userEntity) as $failedLogin) {
            $failedLogin->setDeleted(true);
        }
        $this->entityManager->flush();
    }

    /**
     * @param int $userId
     */
    public function removeAllByUserId($userId)
    {
        // do not remove, just mark deleted!
        foreach ($this->getAllByUserId($userId) as $failedLogin) {
            $failedLogin->setDeleted(true);
        }
        $this->entityManager->flush();
    }
}
