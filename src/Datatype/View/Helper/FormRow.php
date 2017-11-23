<?php

namespace Adminaut\Datatype\View\Helper;

use TwbBundle\Form\View\Helper\TwbBundleFormRow;
use TwbBundle\Options\ModuleOptions;
use Zend\Form\View\Helper\FormElement;

//use Zend\Form\ElementInterface;
//use Zend\Form\LabelAwareInterface;

/**
 * Class FormRow
 * @package Admianut\Datatype\View\Helper
 */
class FormRow extends TwbBundleFormRow
{

    /**
     * @var string
     */
    protected $requiredFormat = '<span class="adminaut-required-input-star">&#42;</span>';

    /**
     * @var ModuleOptions
     */
    protected $twbModuleOptions;

    /**
     * Retrieve the FormElement helper
     *
     * @return Datatype|FormElement
     */
    protected function getElementHelper()
    {
        if ($this->elementHelper) {
            return $this->elementHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementHelper = $this->view->plugin('datatype');
        }

        if (!$this->elementHelper instanceof Datatype) {
            $this->elementHelper = new Datatype($this->twbModuleOptions);
        }

        return $this->elementHelper;
    }

//    /**
//     * Render element's label
//     * @param ElementInterface $oElement
//     * @return string
//     */
//    protected function renderLabel(ElementInterface $oElement)
//    {
//        if ($oElement->getAttribute('required')) {
//            if ($oElement instanceof LabelAwareInterface) {
//                $this->labelAttributes = $oElement->getLabelAttributes();
//            }
//
//            if (isset($this->labelAttributes['class'])) {
//                $this->labelAttributes['class'] .= ' required';
//            } else {
//                $this->labelAttributes['class'] = 'required';
//            }
//        }
//
//        var_dump($this->labelAttributes);
//
//        return parent::renderLabel($oElement);
//    }
}
