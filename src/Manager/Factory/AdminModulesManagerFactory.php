<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\AdminModulesManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminModulesManagerFactory
 * @package Adminaut\Manager\Factory
 */
class AdminModulesManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminModulesManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var array $config */
        $config = $container->get('Config');

        $modules = isset($config['adminaut']['modules']) ? $config['adminaut']['modules'] : [];

        return new AdminModulesManager($modules);
    }
}
