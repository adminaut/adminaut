<?php
namespace Adminaut\Datatype\GooglePlaceId;

use Adminaut\Datatype\GooglePlaceId;
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
     * @return string|GooglePlaceId
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    public function render($datatype) {
        if (! $datatype instanceof GooglePlaceId) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\GooglePlaceId',
                __METHOD__
            ));
        }

        $identifier = 'datatype-googleplaceid-' . $datatype->getName();
        $value = method_exists($datatype, 'getEditValue') ? $datatype->getEditValue() : $datatype->getValue();

        $sRender = '<div class="row datatype-googleplaceid" id="'. $identifier .'">';
        $sRender .= '<div class="col-xs-12"><input type="' . ($datatype->isUseHiddenElement() ? 'hidden' : 'text') . '" 
            name="'.$datatype->getName().'" value="'.$datatype->getEditValue().'" placeholder="'.$datatype->getAttribute('placeholder').'" 
            class="form-control datatype-googleplaceid-input" id="'. $identifier .'-input"></div>';
        $sRender .= '</div><div class="row">';
        $sRender .= '<div class="col-xs-12">
            <input id="'. $identifier .'-search-input" class="controls" type="text" placeholder="'. $this->view->translate('Enter a location') .'">
            <div class="datatype-googleplaceid-map" id="'. $identifier .'-map" style="margin-top: 15px; min-height: 300px;"></div>
        </div>';
        $sRender .= '</div>';

        $sRender .= '<script>appendScript("'. $this->getView()->basepath('adminaut/js/datatype/googleplaceid.js') .'")</script>';
        $sRender .= '<style>
                .controls { display: none; }
                .datatype-googleplaceid-map .controls { display: block; }
                .controls {background-color: #fff;border-radius: 2px;border: 1px solid transparent;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);box-sizing: border-box;font-family: Roboto;font-size: 15px;font-weight: 300;height: 29px;margin-left: 17px;margin-top: 10px;outline: none;padding: 0 11px 0 13px;text-overflow: ellipsis;width: 400px;}
                .controls:focus {border-color: #4d90fe;}
            </style>';

        return $sRender;
    }
}