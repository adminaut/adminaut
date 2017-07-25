<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\AuthStorage;
use Adminaut\Authentication\Storage\CookieStorage;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthStorageFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class AuthStorageFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthStorage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var CookieStorage $cookieStorage */
        $cookieStorage = $serviceLocator->get(CookieStorage::class);

        return new AuthStorage($entityManager, $cookieStorage);
    }
}
