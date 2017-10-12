<?php

namespace Adminaut\Initializer\Factory;

use Adminaut\Initializer\ObjectManagerInitializer;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ObjectManagerInitializerFactory
 * @package Adminaut\Initializer\Factory
 */
class ObjectManagerInitializerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ObjectManagerInitializer
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new ObjectManagerInitializer($entityManager);
    }
}
