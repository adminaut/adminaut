<?php

namespace Adminaut\Mapper;

use Doctrine\ORM\EntityManagerInterface;

use Adminaut\Entity\Resource as ResourceEntity;

use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Class Resource
 * @package Adminaut\Mapper
 */
class Resource
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass = 'Adminaut\Entity\Resource';

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
     * @param $role
     * @return array
     */
    public function getAllByRole($role)
    {
        $er = $this->em->getRepository($this->entityClass);
        return $er->findBy([
            'role' => $role
        ]);
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
     * @param ResourceEntity $entity
     * @return mixed
     */
    public function insert(ResourceEntity $entity)
    {
        return $this->persist($entity);
    }

    /**
     * @param ResourceEntity $entity
     * @return mixed
     */
    public function update(ResourceEntity $entity)
    {
        return $this->persist($entity);
    }

    /**
     * @param ResourceEntity $entity
     */
    public function delete(ResourceEntity $entity)
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