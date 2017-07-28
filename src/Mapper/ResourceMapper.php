<?php

namespace Adminaut\Mapper;

use Adminaut\Entity\Resource as ResourceEntity;

/**
 * Class ResourceMapper
 * @package Adminaut\Mapper
 */
class ResourceMapper extends AbstractMapper
{

    /**
     * @return array
     */
    public function getAll()
    {
        $er = $this->getEntityManager()->getRepository(ResourceEntity::class);
        return $er->findAll();
    }

    /**
     * @param $role
     * @return array
     */
    public function getAllByRole($role)
    {
        $er = $this->getEntityManager()->getRepository(ResourceEntity::class);
        return $er->findBy([
            'role' => $role,
        ]);
    }

    /**
     * @param $name
     * @return object
     */
    public function findByName($name)
    {
        $er = $this->getEntityManager()->getRepository(ResourceEntity::class);
        return $er->findOneBy(['name' => $name]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->getEntityManager()->getRepository(ResourceEntity::class);
        return $er->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @param ResourceEntity $entity
     * @return mixed
     */
    public function insert(ResourceEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * @param ResourceEntity $entity
     * @return mixed
     */
    public function update(ResourceEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * @param ResourceEntity $entity
     */
    public function delete(ResourceEntity $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
