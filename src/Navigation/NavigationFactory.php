<?php
namespace Adminaut\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class NavigationFactory
 * @package Adminaut\Navigation
 */
class NavigationFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new Navigation();
        return $navigation->createService($serviceLocator);
    }
}