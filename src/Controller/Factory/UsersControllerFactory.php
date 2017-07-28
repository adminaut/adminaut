<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UsersController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Mapper\UserMapper;
use Adminaut\Service\UserService;
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

        /** @var UserMapper $userMapper */
        $userMapper = $container->get(UserMapper::class);

        /** @var UserService $userService */
        $userService = $container->get(UserService::class);

        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        return new UsersController(
            $entityManager,
            $userMapper,
            $userService,
            $moduleManager
        );
    }
}
