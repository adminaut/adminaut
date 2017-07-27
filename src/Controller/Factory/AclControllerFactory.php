<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\AclController;
use Adminaut\Mapper\RoleMapper;
use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AclControllerFactory
 * @package Adminaut\Controller\Factory
 */
class AclControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AclController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('Config');

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        /** @var RoleMapper $roleMapper */
        $roleMapper = $container->get(RoleMapper::class);

        return new AclController($config, $accessControlService, $entityManager, $translator, $roleMapper);
    }
}
