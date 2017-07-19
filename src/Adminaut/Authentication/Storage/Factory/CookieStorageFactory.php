<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\CookieStorage;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CookieStorageFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class CookieStorageFactory implements FactoryInterface
{

    /**
     * Constants.
     */
    const CONFIG_ADMINAUT = 'adminaut';
    const CONFIG_ACCESS_TOKEN = 'access-token';

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CookieStorage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cookieOptions = [];

        /** @var array $config */
        $config = $serviceLocator->get('Config');

        /** @var Request $request */
        $request = $serviceLocator->get('Request');

        /** @var Response $response */
        $response = $serviceLocator->get('Response');

        if (isset($config[self::CONFIG_ADMINAUT][self::CONFIG_ACCESS_TOKEN]) && is_array($config[self::CONFIG_ADMINAUT][self::CONFIG_ACCESS_TOKEN])) {
            $cookieOptions = $config[self::CONFIG_ADMINAUT][self::CONFIG_ACCESS_TOKEN];
        }

        $cookieOptions['secure'] = false; // todo: remove
        $cookieOptions['httpOnly'] = false; // todo: remove

        return new CookieStorage($request, $response, $cookieOptions);
    }
}
