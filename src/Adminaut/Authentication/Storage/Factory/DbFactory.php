<?php

namespace Adminaut\Authentication\Storage\Factory;


use Adminaut\Mapper\UserMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Adminaut\Authentication\Storage\Db as StorageDb;

class DbFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new StorageDb(
            $serviceLocator->get(UserMapper::class)
        );
    }
}