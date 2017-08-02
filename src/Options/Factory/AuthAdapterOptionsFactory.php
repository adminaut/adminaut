<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Options\AuthAdapterOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthAdapterOptionsFactory
 * @package Adminaut\Options\Factory
 */
class AuthAdapterOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthAdapterOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new AuthAdapterOptions($adminautOptions->getAuthAdapter());
    }
}
