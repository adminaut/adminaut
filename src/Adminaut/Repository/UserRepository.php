<?php

namespace Adminaut\Repository;

use Adminaut\Entity\UserEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package Adminaut\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getList()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(['user'])
            ->from(UserEntity::class, 'user')
            ->where('user.deleted = 0')
            ->orderBy('user.id', 'ASC');
        return $qb->getQuery()->getResult();
    }
}