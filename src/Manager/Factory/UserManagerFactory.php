<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\UserManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserManagerFactory
 * @package Adminaut\Manager\Factory
 */
class UserManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new UserManager($entityManager);
    }
}
