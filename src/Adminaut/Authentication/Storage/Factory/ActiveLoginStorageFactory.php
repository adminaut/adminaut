<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\ActiveLoginStorage;
use Adminaut\Authentication\Storage\CookieStorage;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ActiveLoginStorageFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class ActiveLoginStorageFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var CookieStorage $cookieStorage */
        $cookieStorage = $serviceLocator->get(CookieStorage::class);

        return new ActiveLoginStorage($entityManager, $cookieStorage);
    }
}
