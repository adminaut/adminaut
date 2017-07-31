<?php

namespace Adminaut\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class AManager
 * @package Adminaut\Manager
 */
abstract class AManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * AManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $entity The instance to make managed and persistent.
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @param null|object|array $entity
     */
    public function flush($entity = null)
    {
        $this->entityManager->flush($entity);
    }
}
