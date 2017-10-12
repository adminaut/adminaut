<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\InstallController;
use Adminaut\Manager\UserManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallControllerFactory
 * @package Adminaut\Controller\Factory
 */
class InstallControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InstallController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var UserManager $userManager */
        $userManager = $container->get(UserManager::class);

        return new InstallController($userManager);
    }
}
