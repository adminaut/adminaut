<?php

namespace Adminaut\Mapper\Factory;

use Adminaut\Mapper\UserFailedLoginMapper;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserFailedLoginMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class UserFailedLoginMapperFactory implements FactoryInterface
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

        return new UserFailedLoginMapper($entityManager);
    }
}
