<?php

namespace Adminaut\Datatype\GoogleMap;

use Adminaut\Datatype\DatatypeHelperTrait;
use Adminaut\Datatype\GoogleMap;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class DetailViewHelper
 * @package Adminaut\Datatype\GoogleMap
 */
class DetailViewHelper extends AbstractHelper
{

    use DatatypeHelperTrait;

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
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param $datatype
     * @return string
     */
    public function render($datatype)
    {
        if (!$datatype instanceof GoogleMap) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type ' . GoogleMap::class,
                __METHOD__
            ));
        }

        $identifier = 'datatype-location-' . $datatype->getName();

        if ($datatype->getConnectedElement()) {
            $data = htmlspecialchars(json_encode(["lat" => $datatype->getValue(), "lng" => $datatype->getConnectedElement()->getValue()]));
        } else {
            $data = $datatype->getEditValue();
        }

        if (!empty($datatype->getValue())) {

            $this->appendScript('adminaut/js/datatype/googlemap.js');

            $sRender = '<div class="row">';
            $sRender .= '<div class="col-xs-12"><div class="datatype-map" style="margin-top: 15px; min-height: 300px;" id="' . $identifier . '" data-useJson="' . $datatype->isUseJSON() . '" data-separator="' . $datatype->getSeparator() . '" data-data="' . $data . '" data-readonly="true"></div></div>';
            $sRender .= '</div>';
            return $sRender;
        }

        return '';
    }
}
