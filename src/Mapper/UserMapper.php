<?php

namespace Adminaut\Mapper;

use Adminaut\Entity\UserEntity;

/**
 * Class UserMapper
 * @package Adminaut\Mapper
 */
class UserMapper extends AbstractMapper implements UserMapperInterface
{

    /**
     * @param string $email
     * @return null|object
     */
    public function findByEmail($email)
    {
        $er = $this->getEntityManager()->getRepository(UserEntity::class);
        return $er->findOneBy(['email' => $email]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->getEntityManager()->getRepository(UserEntity::class);
        return $er->findOneBy([
            'id' => $id,
            'deleted' => 0,
        ]);
    }

    public function findFirst()
    {
        $er = $this->getEntityManager()->getRepository(UserEntity::class);
        return (isset($er->findAll()[0])) ? $er->findAll()[0] : [];
    }

    /**
     * @param object $entity
     * @return object
     */
    public function insert($entity)
    {
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    /**
     * @param object $entity
     * @return object
     */
    public function update($entity)
    {
        $this->flush();
        return $entity;
    }
}
