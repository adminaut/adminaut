<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\UserOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserOptionsFactory
 * @package Adminaut\Options\Factory
 */
class UserOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        return new UserOptions(isset($config['adminaut']['users']) ? $config['adminaut']['users'] : []);
    }
}
