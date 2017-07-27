<?php
namespace Adminaut\Datatype\View\Helper\Factory;


use Adminaut\Datatype\View\Helper\Datatype;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DatatypeFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Datatype
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->getServiceLocator()->get('TwbBundle\Options\ModuleOptions');
        return new Datatype($options);
    }
}