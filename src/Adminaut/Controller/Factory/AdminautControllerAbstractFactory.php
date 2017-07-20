<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AdminautControllerAbstractFactory
 * @package Adminaut\Controller\Factory
 */
class AdminautControllerAbstractFactory implements AbstractFactoryInterface
{

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (fnmatch('*Controller', $requestedName)) ? true : false;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (class_exists($requestedName)) {
            /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
            $parentLocator = $serviceLocator->getServiceLocator();

            return new $requestedName(
                $parentLocator->get('config'),
                $parentLocator->get(AccessControlService::class),
                $parentLocator->get(EntityManager::class),
                $parentLocator->get(Translator::class)
            );
        }

        return false;
    }
}
