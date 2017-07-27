<?php

namespace Adminaut\Service\Factory;

use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AccessControlServiceFactory
 * @package Adminaut\Service\Factory
 */
class AccessControlServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AccessControlService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /*return new \Adminaut\Service\AccessControl(
            $container->get('config'),
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('UserMapper'),
            $container->get('RoleMapper'),
            $container->get('ResourceMapper'),
            $container->get('ResourceMapper')
        );*/

        /** @var array $config */
        $config = $container->get('config');

        $roles = isset($config["adminaut"]['roles']) ? $config["adminaut"]['roles'] : [];

        return new AccessControlService($roles);
    }
}
