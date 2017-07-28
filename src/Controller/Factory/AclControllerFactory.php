<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\AclController;
use Adminaut\Mapper\RoleMapper;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AclControllerFactory
 * @package Adminaut\Controller\Factory
 */
class AclControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AclController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var RoleMapper $roleMapper */
        $roleMapper = $container->get(RoleMapper::class);

        return new AclController($entityManager, $roleMapper);
    }
}
