<?php

namespace Adminaut\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Module
 * @package Adminaut\Repository
 */
class Module extends EntityRepository implements ModuleInterface
{

    /**
     * @return array
     */
    public function getList()
    {
        // todo: use Criteria
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e')
            ->from($this->getEntityName(), 'e')
            ->where('user.deleted = 0')
            ->orderBy('user.id', 'ASC');
        return $qb->getQuery()->getResult();
    }
}
