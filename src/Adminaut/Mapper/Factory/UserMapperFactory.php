<?php
namespace Adminaut\Mapper\Factory;

use Adminaut\Options\UserOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class UserMapperFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new \Adminaut\Mapper\UserMapper(
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class),
            $serviceLocator->get(UserOptions::class)
        );
    }
}