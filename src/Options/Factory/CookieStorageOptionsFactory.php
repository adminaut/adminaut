<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Options\CookieStorageOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CookieStorageOptionsFactory
 * @package Adminaut\Options\Factory
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

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new CookieStorageOptions($adminautOptions->getCookieStorage());
    }
}
