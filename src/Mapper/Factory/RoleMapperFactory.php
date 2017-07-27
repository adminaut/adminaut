<?php
namespace Adminaut\Mapper\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RoleMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class RoleMapperFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new \Adminaut\Mapper\RoleMapper(
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class)
        );
    }
}