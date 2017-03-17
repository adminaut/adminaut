<?php
namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\Db;
use Adminaut\Mapper\UserMapper;
use Adminaut\Options\UserOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DbFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
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
        return new Db(
            $serviceLocator->get(UserMapper::class),
            $serviceLocator->get(UserOptions::class)
        );
    }
}