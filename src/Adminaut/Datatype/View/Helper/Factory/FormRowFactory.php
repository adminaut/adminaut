<?php
namespace Adminaut\Datatype\View\Helper\Factory;

use Adminaut\Datatype\View\Helper\FormRow;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FormRowFactory
 * @package Adminaut\Datatype\View\Helper\Factory
 */
class FormRowFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FormRow
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->getServiceLocator()->get('TwbBundle\Options\ModuleOptions');
        return new FormRow($options);
    }
}