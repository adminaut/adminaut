<?php
namespace Adminaut\Datatype\GoogleMap;

use Adminaut\Datatype\GoogleMap;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

class FormViewHelper extends AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|GoogleMap
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    public function render($datatype) {
        if (! $datatype instanceof GoogleMap) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\GoogleMap',
                __METHOD__
            ));
        }

        $identifier = 'datatype-map-' . $datatype->getName() . '-' . time();
        $value = method_exists($datatype, 'getEditValue') ? $datatype->getEditValue() : $datatype->getValue();

        $sRender = '<div class="row">';
        if($datatype->getConnectedElement()) {

            $sRender .= '<div class="col-xs-6"><input type="' . ($datatype->isUseHiddenElement() ? 'hidden' : 'text') . '" 
            name="'.$datatype->getName().'" value="'.$datatype->getValue().'" placeholder="'.$datatype->getAttribute('placeholder').'" 
            class="form-control" id="'. $identifier .'-lat"></div>';
            $sRender .= '<div class="col-xs-6"><input type="' . ($datatype->isUseHiddenElement() ? 'hidden' : 'text') . '" 
            name="' . $datatype->getConnectedElement()->getName() . '" value="' . $datatype->getConnectedElement()->getValue() . '" 
            placeholder="' . $datatype->getConnectedElement()->getAttribute('placeholder') . '" 
            class="form-control" id="' . $identifier . '-lng"></div>';

        } else {
            $sRender .= '<div class="col-xs-12"><input type="' . ($datatype->isUseHiddenElement() ? 'hidden' : 'text') . '" 
            name="'.$datatype->getName().'" value="'.$datatype->getEditValue().'" placeholder="'.$datatype->getAttribute('placeholder').'" 
            class="form-control" id="'. $identifier .'-coords" data-useJson="'. $datatype->isUseJSON() .'" data-separator="'.$datatype->getSeparator().'"></div>';
        }
        $sRender .= '</div><div class="row">';
        $sRender .= '<div class="col-xs-12"><div class="datatype-map" style="margin-top: 15px; min-height: 300px;" id="'. $identifier .'"></div></div>';
        $sRender .= '</div>';

        $sRender .= '<script>appendScript("'. $this->getView()->basepath('adminaut/js/datatype/googlemap.js') .'")</script>';

        return $sRender;
    }
}