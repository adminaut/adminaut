<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\CookieStorageOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CookieStorageOptionsFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class CookieStorageOptionsFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CookieStorageOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('Config');

        if (
            isset($config['adminaut'][CookieStorageOptions::CONFIG_KEY])
            && is_array($config['adminaut'][CookieStorageOptions::CONFIG_KEY])
        ) {
            return new CookieStorageOptions($config['adminaut'][CookieStorageOptions::CONFIG_KEY]);
        }

        return new CookieStorageOptions();
    }
}
