<?php

namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Adapter\AuthAdapterOptions;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthAdapterFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
class AuthAdapterFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var AuthAdapterOptions $options */
        $options = $serviceLocator->get(AuthAdapterOptions::class);

        return new AuthAdapter($entityManager, $options);
    }
}
