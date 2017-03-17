<?php
namespace Adminaut\Options\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserOptionsFactory
 * @package Adminaut\Options\Factory
 */
class UserOptionsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        return new \Adminaut\Options\UserOptions(isset($config['user']) ? $config['user'] : []);
    }
}