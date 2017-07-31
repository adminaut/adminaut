<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UsersController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\UserManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UsersControllerFactory
 * @package Adminaut\Controller\Factory
 */
class UsersControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UsersController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var UserManager $userManager */
        $userManager = $container->get(UserManager::class);

        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        return new UsersController($entityManager, $userManager, $moduleManager);
    }
}
