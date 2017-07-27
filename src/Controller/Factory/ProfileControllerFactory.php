<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ProfileController;
use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
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

        /** @var array $config */
        $config = $container->get('Config');

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        return new ProfileController($config, $accessControlService, $entityManager, $translator);
    }
}
