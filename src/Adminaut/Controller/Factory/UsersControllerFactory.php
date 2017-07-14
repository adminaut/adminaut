<?php
namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UsersController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Mapper\UserMapper;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\UserService;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UsersControllerFactory
 * @package Adminaut\Controller\Factory
 */
class UsersControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new UsersController(
            $parentLocator->get('config'),
            $parentLocator->get(AccessControlService::class),
            $parentLocator->get(\Doctrine\ORM\EntityManager::class),
            $parentLocator->get(Translator::class),
            $parentLocator->get(UserMapper::class),
            $parentLocator->get(UserService::class),
            $parentLocator->get(ModuleManager::class)
        );
    }
}