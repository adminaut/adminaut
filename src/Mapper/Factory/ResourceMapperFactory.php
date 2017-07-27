<?php

namespace Adminaut\Mapper\Factory;

use Adminaut\Mapper\Resource as ResourceMapper;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ResourceMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class ResourceMapperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ResourceMapper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new ResourceMapper($entityManager);
    }
}
