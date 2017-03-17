<?php

namespace Adminaut\Form\View\Helper\Factory;

use Adminaut\Form\View\Helper\FormElement;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TwbBundleFormElementFactory
 * @package Adminaut\Form\View\Helper\Factory
 */
class FormElementFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FormElement
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->getServiceLocator()->get('TwbBundle\Options\ModuleOptions');
        return new FormElement($options);
    }
}