<?php
namespace Adminaut\Datatype\View\Helper;

use TwbBundle\Form\View\Helper\TwbBundleFormRow;
use TwbBundle\Options\ModuleOptions;
use Zend\Form\View\Helper\FormElement;

/**
 * Class FormRow
 * @package Admianut\Datatype\View\Helper
 */
class FormRow extends TwbBundleFormRow
{
    /** @var ModuleOptions */
    protected $twbModuleOptions;

    public function __construct($twbModuleOptions)
    {
        $this->twbModuleOptions = $twbModuleOptions;
    }

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

        if (! $this->elementHelper instanceof Datatype) {
            $this->elementHelper = new Datatype($this->twbModuleOptions);
        }

        return $this->elementHelper;
    }
}
