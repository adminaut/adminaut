<?php

namespace Adminaut\Repository;

use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserFailedLoginEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserFailedLoginRepository
 * @package Adminaut\Repository
 */
class UserFailedLoginRepository extends EntityRepository
{
    /**
     * @param UserEntity $userEntity
     * @return array|UserFailedLoginEntity[]
     */
    public function findByUser(UserEntity $userEntity)
    {
        return $this->findBy(['user' => $userEntity, 'deleted' => false]);
    }

    /**
     * @param $userId
     * @return array|UserFailedLoginEntity[]
     */
    public function findByUserId($userId)
    {
        return $this->findBy(['userId' => $userId, 'deleted' => false]);
    }
}
