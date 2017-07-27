<?php

namespace Adminaut\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Role
 * @package Adminaut\Repository
 */
class Role extends EntityRepository
{
    /**
     * @return array
     */
    public function getList()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('role')
            ->from('\Adminaut\Entity\Role', 'role');
        return $qb->getQuery()->getResult();
    }
}