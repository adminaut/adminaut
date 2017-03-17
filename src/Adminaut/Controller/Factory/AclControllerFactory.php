<?php
namespace Adminaut\Controller\Factory;

use Adminaut\Controller\AclController;
use Adminaut\Mapper\RoleMapper;
use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclControllerFactory implements FactoryInterface
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
        $sm = $serviceLocator->getServiceLocator();

        return new AclController(
            $sm->get(AccessControlService::class),
            $sm->get(\Doctrine\ORM\EntityManager::class),
            $sm->get('config'),
            $sm->get(RoleMapper::class)
        );
    }
}