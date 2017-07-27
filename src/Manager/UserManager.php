<?php

namespace Adminaut\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class UserManager
 * @package Adminaut\Manager
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // todo: implement desired methods
}
