<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\AdminautModulesManager;
use Adminaut\Options\AdminautOptions;
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

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new AdminautModulesManager($adminautOptions->getModules());
    }
}
