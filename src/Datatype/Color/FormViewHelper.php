<?php

namespace Adminaut\Datatype\Color;

use Adminaut\Datatype\Color;
use Adminaut\Datatype\DatatypeHelperTrait;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormColor as ZendFormColor;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\Color
 */
class FormViewHelper extends ZendFormColor
{
    use DatatypeHelperTrait;

    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param Color|ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {

        $this->appendScript('adminaut/themes/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.js');
        $this->appendStylesheet('adminaut/themes/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.css');
        $this->appendStylesheet('adminaut/css/datatype/color.css');

        $element->setAttribute('type', 'text');

        $render = parent::render($element);

        $render .= '<script>$(".datatype-color").colorpicker({align:"left",customClass: \'colorpicker-2x\',sliders: {saturation: {maxLeft: 200,maxTop: 200},hue: {maxTop: 200},alpha: {maxTop: 200}}';

        if($element->getFormat()) {
            $render .= ',format:"'.$element->getFormat().'"';
        }

        $render .= '})</script>';

        return $render;
    }

    /**
     * Determine input type to use
     *
     * @param  ElementInterface $element
     * @return string
     */
    protected function getType(ElementInterface $element)
    {
        return 'text';
    }
}
