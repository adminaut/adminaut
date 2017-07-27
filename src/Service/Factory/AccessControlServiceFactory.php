<?php
namespace Adminaut\Service\Factory;

use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AccessControlServiceFactory
 * @package Adminaut\Service\Factory
 */
class AccessControlServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /*return new \Adminaut\Service\AccessControl(
            $serviceLocator->get('config'),
            $serviceLocator->get('Doctrine\ORM\EntityManager'),
            $serviceLocator->get('UserMapper'),
            $serviceLocator->get('RoleMapper'),
            $serviceLocator->get('ResourceMapper'),
            $serviceLocator->get('ResourceMapper')
        );*/

        $config = $serviceLocator->get('config');
        $roles = isset($config["adminaut"]['roles']) ? $config["adminaut"]['roles'] : [];
        return new AccessControlService($roles);
    }

}