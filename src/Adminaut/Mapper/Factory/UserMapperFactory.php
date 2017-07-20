<?php

namespace Adminaut\Mapper\Factory;

use Adminaut\Mapper\UserMapper;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class UserMapperFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserMapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new UserMapper($entityManager);
    }
}
