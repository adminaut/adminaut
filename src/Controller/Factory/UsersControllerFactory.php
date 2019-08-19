<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UsersController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\UserManager;
use Adminaut\Options\UsersOptions;
use Adminaut\Service\MailService;
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

        /** @var UsersOptions $usersOptions */
        $usersOptions = $container->get(UsersOptions::class);

        /** @var array $config */
        $config = $container->get('config');

        /** @var MailService|null $mailService */
        $mailService = null;

        if (isset($config['adminaut']['mail_service']) && isset($config['adminaut']['mail_service']['enabled']) && true === $config['adminaut']['mail_service']['enabled']) {
            $mailService = $container->get(MailService::class);
        }

        return new UsersController($entityManager, $userManager, $moduleManager, $mailService, $usersOptions);
    }
}
