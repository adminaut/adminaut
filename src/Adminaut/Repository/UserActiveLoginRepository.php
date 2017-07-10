<?php

namespace Adminaut\Repository;

use Adminaut\Entity\UserActiveLoginEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserActiveLoginRepository
 * @package Adminaut\Repository
 */
class UserActiveLoginRepository extends EntityRepository
{

    /**
     * @param string $accessTokenHash
     * @return null|object|UserActiveLoginEntity
     */
    public function findOneByAccessTokenHash($accessTokenHash)
    {
        return $this->findOneBy([
            'accessTokenHash' => (string)$accessTokenHash,
        ]);
    }
}
