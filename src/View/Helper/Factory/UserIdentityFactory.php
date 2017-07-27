<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\View\Helper\UserIdentity;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserIdentityFactory
 * @package Adminaut\View\Helper\Factory
 */
class UserIdentityFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserIdentity
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        return new UserIdentity($authenticationService);
    }
}
