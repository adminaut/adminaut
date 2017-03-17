<?php
namespace Adminaut\Service\Factory;


use Adminaut\Mapper\RoleMapper;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\UserOptions;
use Adminaut\Service\AccessControlService;
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
        return new \Adminaut\Service\UserService(
            $serviceLocator->get((string) AccessControlService::class),
            $serviceLocator->get(RoleMapper::class),
            $serviceLocator->get(UserMapper::class),
            $serviceLocator->get('UserAuthService'),
            $serviceLocator->get(UserOptions::class)
        );
    }

}