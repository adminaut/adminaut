<?php
namespace Adminaut\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FileManagerViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class FileManagerViewHelperFactory implements FactoryInterface
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

        $option = $parentLocator->get('FileManagerOptions');
        $viewHelper = new \Adminaut\View\Helper\FileManager();
        $viewHelper->setService($parentLocator->get('FileManager'));
        $viewHelper->setParams($option->toArray());
        return $viewHelper;
    }
}