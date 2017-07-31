<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\AdminautModulesManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminautModulesManagerFactory
 * @package Adminaut\Manager\Factory
 */
class AdminautModulesManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminautModulesManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var array $config */
        $config = $container->get('Config');

        $modules = isset($config['adminaut']['modules']) ? $config['adminaut']['modules'] : [];

        return new AdminautModulesManager($modules);
    }
}
