<?php
namespace Adminaut\View\Helper\Factory;

use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IsAllowedViewHelperFactory
 * @package Adminaut\Factory\View\Helper
 */
class IsAllowedViewHelperFactory implements FactoryInterface
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

        $viewHelper = new \Adminaut\View\Helper\IsAllowed();
        $viewHelper->setAclService($parentLocator->get(AccessControlService::class));
        $viewHelper->setAuthService($parentLocator->get('UserAuthService'));
        return $viewHelper;
    }
}