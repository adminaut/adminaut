<?php
namespace Adminaut\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserIdentityViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class UserIdentityViewHelperFactory implements FactoryInterface
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

        $viewHelper = new \Adminaut\View\Helper\UserIdentity();
        $viewHelper->setAuthService($parentLocator->get('UserAuthService'));
        return $viewHelper;
    }
}