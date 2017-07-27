<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\UserAuthentication;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserAuthenticationFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class UserAuthenticationFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserAuthentication
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        return new UserAuthentication($authenticationService);
    }
}
