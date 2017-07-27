<?php

namespace Adminaut\Mapper;

use Adminaut\Entity\UserEntity;
use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class UserMapper
 * @package Adminaut\Mapper
 */
class UserMapper extends AbstractDbMapper
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * UserMapper constructor.
     * @param $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $email
     * @return object
     */
    public function findByEmail($email)
    {
        $er = $this->entityManager->getRepository(UserEntity::class);
        return $er->findOneBy(['email' => $email]);
    }

    /**
     * @param $username
     * @return object
     */
    public function findByUsername($username)
    {
        $er = $this->entityManager->getRepository(UserEntity::class);
        return $er->findOneBy(['username' => $username]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->entityManager->getRepository(UserEntity::class);
        return $er->findOneBy([
            'id' => $id,
            'deleted' => 0,
        ]);
    }

    public function findFirst()
    {
        $er = $this->entityManager->getRepository(UserEntity::class);
        return (isset($er->findAll()[0])) ? $er->findAll()[0] : [];
    }

    /**
     * @param array|object $entity
     * @param null $tableName
     * @param HydratorInterface|null $hydrator
     * @return mixed
     */
    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    /**
     * @param $entity
     * @param null $where
     * @param null $tableName
     * @param HydratorInterface|null $hydrator
     * @return mixed
     */
    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    /**
     * @param $entity
     * @return mixed
     */
    protected function persist($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
}
