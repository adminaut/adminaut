<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ProfileController;
use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProfileControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ProfileControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new ProfileController(
            $parentLocator->get('config'),
            $parentLocator->get(AccessControlService::class),
            $parentLocator->get(EntityManager::class),
            $parentLocator->get(Translator::class)
        );
    }
}
