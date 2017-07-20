<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ProfileController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProfileControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ProfileControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ProfileController();
    }
}
