<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Options\UsersOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UsersOptionsFactory
 * @package Adminaut\Options\Factory
 */
class UsersOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UsersOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new UsersOptions($adminautOptions->getUsers());
    }
}
