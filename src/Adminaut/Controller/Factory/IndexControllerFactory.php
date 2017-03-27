<?php
namespace Adminaut\Controller\Factory;

use Adminaut\Controller\IndexController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Class IndexControllerFactory
 * @package Adminaut\Controller\Factory
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new IndexController(
            $parentLocator->get('config')
        );
    }


}