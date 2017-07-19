<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\CookieStorageOptions;
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
     * @param ServiceLocatorInterface $serviceLocator
     * @return CookieStorage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Request $request */
        $request = $serviceLocator->get('Request');

        /** @var Response $response */
        $response = $serviceLocator->get('Response');

        /** @var CookieStorageOptions $options */
        $options = $serviceLocator->get(CookieStorageOptions::class);

        return new CookieStorage($request, $response, $options);
    }
}
