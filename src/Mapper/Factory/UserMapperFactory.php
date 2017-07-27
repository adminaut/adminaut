<?php

namespace Adminaut\Mapper\Factory;

use Adminaut\Mapper\UserMapper;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserMapperFactory
 * @package Adminaut\Mapper\Factory
 */
class UserMapperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserMapper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new UserMapper($entityManager);
    }
}
