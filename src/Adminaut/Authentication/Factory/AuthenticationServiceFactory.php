<?php
namespace Adminaut\Authentication\Factory;

use Adminaut\Authentication\Adapter\AdapterChain;
use Adminaut\Authentication\Storage\Db;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthenticationServiceFactory
 * @package Adminaut\Authentication\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $parentLocator = $serviceLocator->getServiceLocator();
        return new \Zend\Authentication\AuthenticationService(
            $serviceLocator->get(Db::class),
            $serviceLocator->get(AdapterChain::class)
        );
    }

}