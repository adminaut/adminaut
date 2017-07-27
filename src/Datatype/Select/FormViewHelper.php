<?php
namespace Adminaut\Datatype\Select;

use Adminaut\Datatype\DateTime;
use TwbBundle\Form\View\Helper\TwbBundleFormElement;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormSelect as ZendFormSelect;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\Select
 */
class FormViewHelper extends ZendFormSelect
{
    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param DateTime $element
     * @return string
     */
	public function render(ElementInterface $element)
    {
        $element->setAttribute('type', 'select');
        $render = parent::render($element);
        $render .= '<script>' . PHP_EOL;
        $render .= '    appendScript("'. $this->getView()->basepath('adminaut/themes/adminlte/plugins/select2/select2.full.min.js') .'");' . PHP_EOL;
        $render .= '    appendStyle("'. $this->getView()->basepath('adminaut/themes/adminlte/plugins/select2/select2.css') .'");' . PHP_EOL;
        $render .= '    $("select").select2();' . PHP_EOL;
        $render .= '</script>';
        return $render;
    }
}