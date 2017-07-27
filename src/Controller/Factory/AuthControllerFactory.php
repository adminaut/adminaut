<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\AuthController;
use Adminaut\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthControllerFactory
 * @package Adminaut\Controller\Factory
 */
class AuthControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        /** @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new AuthController($authenticationService, $userService);
    }
}
