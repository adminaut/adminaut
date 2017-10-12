<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\CookieStorage;
use Adminaut\Options\CookieStorageOptions;
use Interop\Container\ContainerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CookieStorageFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class CookieStorageFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CookieStorage
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var Request $request */
        $request = $container->get('Request');

        /** @var Response $response */
        $response = $container->get('Response');

        /** @var CookieStorageOptions $options */
        $options = $container->get(CookieStorageOptions::class);

        return new CookieStorage($request, $response, $options);
    }
}
