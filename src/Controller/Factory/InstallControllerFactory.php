<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\InstallController;
use Adminaut\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
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

        /** @var UserService $userService */
        $userService = $container->get(UserService::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        return new InstallController($userService, $translator);
    }
}
