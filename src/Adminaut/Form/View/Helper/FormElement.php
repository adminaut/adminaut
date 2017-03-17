<?php

namespace Adminaut\Form\View\Helper;

use TwbBundle\Form\View\Helper\TwbBundleFormElement;

use TwbBundle\Options\ModuleOptions;
use Zend\Form\ElementInterface;

/**
 * Class FormElement
 * @package Adminaut\Form\View\Helper
 */
class FormElement extends TwbBundleFormElement
{
    public function __construct(ModuleOptions $options)
    {
        $this->addClass('Adminaut\Form\Element\Reference', '\Adminaut\Form\View\Helper\FormReference');

        parent::__construct($options);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element) {
        return parent::render($element);
    }
}