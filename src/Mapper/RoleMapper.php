<?php

namespace Adminaut\Mapper;

use Doctrine\ORM\EntityManagerInterface;

use Adminaut\Entity\Role as RoleEntity;

use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Class RoleMapper
 * @package Adminaut\Mapper
 */
class RoleMapper
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass = 'Adminaut\Entity\Role';

    /**
     * Role constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $er = $this->em->getRepository($this->entityClass);
        return $er->findAll();
    }

    /**
     * @param $name
     * @return object
     */
    public function findByName($name)
    {
        $er = $this->em->getRepository($this->entityClass);
        return $er->findOneBy(['name' => $name]);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->entityClass);
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
        return $this->persist($entity);
    }

    /**
     * @param RoleEntity $entity
     * @return mixed
     */
    public function update(RoleEntity $entity)
    {
        return $this->persist($entity);
    }

    /**
     * @param RoleEntity $entity
     */
    public function delete(RoleEntity $entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param $entity
     * @return mixed
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
}