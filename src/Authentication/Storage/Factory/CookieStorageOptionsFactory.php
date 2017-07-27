<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\CookieStorageOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CookieStorageOptionsFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class CookieStorageOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CookieStorageOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var array $config */
        $config = $container->get('Config');

        if (
            isset($config['adminaut'][CookieStorageOptions::CONFIG_KEY])
            && is_array($config['adminaut'][CookieStorageOptions::CONFIG_KEY])
        ) {
            return new CookieStorageOptions($config['adminaut'][CookieStorageOptions::CONFIG_KEY]);
        }

        return new CookieStorageOptions();
    }
}
