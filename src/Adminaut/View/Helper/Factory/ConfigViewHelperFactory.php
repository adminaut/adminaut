<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\View\Helper\ConfigViewHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ConfigViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class ConfigViewHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        $config = $parentLocator->get('config');
        $viewHelper = new ConfigViewHelper($config);
        return $viewHelper;
    }
}
