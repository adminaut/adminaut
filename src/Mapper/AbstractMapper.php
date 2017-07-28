<?php

namespace Adminaut\Mapper;

use Doctrine\ORM\EntityManager;

/**
 * Class AbstractMapper
 * @package Adminaut\Mapper
 */
abstract class AbstractMapper
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AbstractMapper constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param object $entity
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @param null|object $entity
     */
    public function flush($entity = null)
    {
        $this->entityManager->flush($entity);
    }
}
