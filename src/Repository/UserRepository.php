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
     * @return UserEntity[]|array
     * @deprecated Use findAll() instead.
     */
    public function getList()
    {
        return $this->findAll();
    }

    /**
     * @return UserEntity[]|array
     */
    public function findAll()
    {
        return $this->findBy([
            'deleted' => false,
        ], [
            'id' => 'ASC',
        ]);
    }

    /**
     * @param int $id
     * @return UserEntity|null|object
     */
    public function findOneById($id)
    {
        return $this->findOneBy([
            'id' => (int)$id,
            'deleted' => false,
        ]);
    }

    /**
     * @param string $email
     * @param array|null $orderBy
     * @return UserEntity|null|object
     */
    public function findOneByEmail($email, array $orderBy = null)
    {
        return $this->findOneBy([
            'email' => (string)$email,
            'deleted' => false,
        ], $orderBy);
    }
}
