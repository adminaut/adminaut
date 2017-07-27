<?php

namespace Adminaut\Service\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Mapper\RoleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserServiceFactory
 * @package Adminaut\Service\Factory
 */
class UserServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AccessControlService $acs */
        $acs = $container->get(AccessControlService::class);

        /** @var RoleMapper $rm */
        $rm = $container->get(RoleMapper::class);

        /** @var UserMapper $um */
        $um = $container->get(UserMapper::class);

        /** @var AuthenticationService $as */
        $as = $container->get(AuthenticationService::class);

        return new UserService($acs, $rm, $um, $as);
    }
}
