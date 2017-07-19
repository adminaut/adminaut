<?php

namespace Adminaut\Service\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Mapper\RoleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\UserOptions;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserServiceFactory
 * @package Adminaut\Service\Factory
 */
class UserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserService(
            $serviceLocator->get(AccessControlService::class),
            $serviceLocator->get(RoleMapper::class),
            $serviceLocator->get(UserMapper::class),
            $serviceLocator->get(AuthenticationService::class),
            $serviceLocator->get(UserOptions::class)
        );
    }
}
