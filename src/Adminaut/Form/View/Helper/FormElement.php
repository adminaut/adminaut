<?php
namespace Adminaut\Form\View\Helper;


use TwbBundle\Form\View\Helper\TwbBundleFormElement;
use TwbBundle\Options\ModuleOptions;

class FormElement extends TwbBundleFormElement
{
    public function __construct(ModuleOptions $options)
    {
        $this->addType('single_checkbox', 'formcheckbox');

        parent::__construct($options);
    }

}