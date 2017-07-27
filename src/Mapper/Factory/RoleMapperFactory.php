<?php

namespace Adminaut\Mapper\Factory;

use Adminaut\Mapper\RoleMapper;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RoleMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class RoleMapperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleMapper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new RoleMapper($entityManager);
    }
}
