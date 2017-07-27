<?php
namespace Adminaut\Datatype\GoogleMap;

use Adminaut\Datatype\GoogleMap;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

class DetailViewHelper extends AbstractHelper
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

        $identifier = 'datatype-location-' . $datatype->getName();


        if($datatype->getConnectedElement()) {
            $data = htmlspecialchars(json_encode(["lat" => $datatype->getValue(), "lng" => $datatype->getConnectedElement()->getValue()]));

        } else {
            $data = $datatype->getEditValue();
        }

        if(!empty($datatype->getValue())) {
            $sRender = '<div class="row">';
            $sRender .= '<div class="col-xs-12"><div class="datatype-map" style="margin-top: 15px; min-height: 300px;" id="' . $identifier . '" data-useJson="' . $datatype->isUseJSON() . '" data-separator="' . $datatype->getSeparator() . '" data-data="' . $data . '" data-readonly="true"></div></div>';
            $sRender .= '</div>';

            $sRender .= '<script>appendScript("' . $this->getView()->basepath('adminaut/js/datatype/googlemap.js') . '")</script>';
            return $sRender;
        } else {
            return '';
        }
    }
}