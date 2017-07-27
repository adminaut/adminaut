<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UsersController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Mapper\UserMapper;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
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

        /** @var array $config */
        $config = $container->get('Config');

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        /** @var UserMapper $userMapper */
        $userMapper = $container->get(UserMapper::class);

        /** @var UserService $userService */
        $userService = $container->get(UserService::class);

        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        return new UsersController(
            $config,
            $accessControlService,
            $entityManager,
            $translator,
            $userMapper,
            $userService,
            $moduleManager
        );
    }
}
