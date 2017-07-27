<?php

namespace Adminaut\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class RoleManager
 * @package Adminaut\Manager
 */
class RoleManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * RoleManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // todo: implement desired methods
}
