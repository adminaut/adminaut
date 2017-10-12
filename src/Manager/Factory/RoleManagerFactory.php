<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\RoleManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RoleManagerFactory
 * @package Adminaut\Manager\Factory
 */
class RoleManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new RoleManager($entityManager);
    }
}
