<?php

namespace Adminaut\Repository;

use Adminaut\Entity\UserAccessTokenEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserAccessTokenRepository
 * @package Adminaut\Repository
 */
class UserAccessTokenRepository extends EntityRepository
{

    /**
     * @param string $hash
     * @return null|object|UserAccessTokenEntity
     */
    public function findOneByHash($hash)
    {
        return $this->findOneBy([
            'hash' => (string)$hash,
        ]);
    }
}
