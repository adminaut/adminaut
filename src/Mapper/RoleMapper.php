<?php

namespace Adminaut\Mapper;

use Adminaut\Entity\Role as RoleEntity;

/**
 * Class RoleMapper
 * @package Adminaut\Mapper
 */
class RoleMapper extends AbstractMapper
{

    /**
     * @return array
     */
    public function getAll()
    {
        $er = $this->getEntityManager()->getRepository(RoleEntity::class);
        return $er->findAll();
    }

    /**
     * @param $name
     * @return object
     */
    public function findByName($name)
    {
        $er = $this->getEntityManager()->getRepository(RoleEntity::class);
        return $er->findOneBy(['name' => $name]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->getEntityManager()->getRepository(RoleEntity::class);
        return $er->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @param RoleEntity $entity
     * @return mixed
     */
    public function insert(RoleEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * @param RoleEntity $entity
     * @return mixed
     */
    public function update(RoleEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * @param RoleEntity $entity
     */
    public function delete(RoleEntity $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
