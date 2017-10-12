<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ProfileController;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProfileControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ProfileControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ProfileController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new ProfileController($entityManager);
    }
}
