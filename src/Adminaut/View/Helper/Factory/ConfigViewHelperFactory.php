<?php
namespace Adminaut\View\Helper\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigViewHelperFactory implements FactoryInterface
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

        $config = $parentLocator->get('config');
        $viewHelper = new \Adminaut\View\Helper\ConfigViewHelper($config);
        return $viewHelper;
    }
}