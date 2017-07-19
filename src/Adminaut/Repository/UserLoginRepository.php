<?php

namespace Adminaut\Repository;

use Adminaut\Entity\UserEntity;
use Adminaut\Entity\UserLoginEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserLoginRepository
 * @package Adminaut\Repository
 */
class UserLoginRepository extends EntityRepository
{

    /**
     * @param int $userId
     * @return UserLoginEntity[]|array
     */
    public function findActiveFailedByUserId($userId)
    {
        return $this->findBy([
            'userId' => $userId,
            'type' => UserLoginEntity::TYPE_FAILED,
            'active' => true,
            'deleted' => false,
        ], [
            'id' => 'ASC',
        ]);
    }

    /**
     * @param UserEntity $user
     * @return UserLoginEntity[]|array
     */
    public function findActiveFailedByUser(UserEntity $user)
    {
        return $this->findActiveFailedByUserId($user->getId());
    }
}
