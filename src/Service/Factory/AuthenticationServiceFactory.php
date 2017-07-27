<?php
namespace Adminaut\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthenticationServiceFactory
 * @package Adminaut\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $parentLocator = $serviceLocator->getServiceLocator();
        return new \Zend\Authentication\AuthenticationService(
            $serviceLocator->get('Adminaut\Authentication\Storage\Db'),
            $serviceLocator->get('Adminaut\Authentication\Adapter\AdapterChain')
        );
    }

}