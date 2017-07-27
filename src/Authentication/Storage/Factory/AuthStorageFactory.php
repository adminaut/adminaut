<?php

namespace Adminaut\Authentication\Storage\Factory;

use Adminaut\Authentication\Storage\AuthStorage;
use Adminaut\Authentication\Storage\CookieStorage;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthStorageFactory
 * @package Adminaut\Authentication\Storage\Factory
 */
class AuthStorageFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthStorage
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var CookieStorage $cookieStorage */
        $cookieStorage = $container->get(CookieStorage::class);

        return new AuthStorage($entityManager, $cookieStorage);
    }
}
