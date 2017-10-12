<?php

namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Options\AuthAdapterOptions;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthAdapterFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
class AuthAdapterFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var AuthAdapterOptions $options */
        $options = $container->get(AuthAdapterOptions::class);

        return new AuthAdapter($entityManager, $options);
    }
}
