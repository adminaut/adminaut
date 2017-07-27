<?php
namespace Adminaut\Mapper\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class ResourceMapperFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new \Adminaut\Mapper\Resource(
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class)
        );
    }
}