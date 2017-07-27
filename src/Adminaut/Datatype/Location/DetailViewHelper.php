<?php
namespace Adminaut\Datatype\Location;

use Adminaut\Datatype\Location;
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
     * @return string|Location
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    public function render($datatype) {
        if (! $datatype instanceof Location) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\Location',
                __METHOD__
            ));
        }

        $identifier = 'datatype-location-' . $datatype->getName();
        $value = method_exists($datatype, 'getEditValue') ? $datatype->getEditValue() : $datatype->getValue();

        if(!empty($datatype->getValue())) {
            $attributes = $datatype->getAttributes();
            unset($attributes['type']);
            if ($datatype->getLongitudeElement()) {
                $attributes['data-longitude-element'] = true;
                $attributes['data-longitude-element-name'] = $datatype->getLongitudeElement()->getName();
            }

            if ($datatype->getGooglePlaceIdElement()) {
                $attributes['data-google-place-id-element'] = true;
                $attributes['data-google-place-id-element-name'] = $datatype->getGooglePlaceIdElement()->getName();
            }

            $attributes['data-value'] = $this->getJsonValue($datatype);
            $attributes['data-readonly'] = true;

            $sRender = '<div class="datatype-location" ' . $this->createAttributesString($attributes) . '></div>';

            $sRender .= '<script>appendScript("' . $this->getView()->basepath('adminaut/js/datatype/location.js') . '")</script>';
            return $sRender;
        } else {
            return '';
        }
    }

    /**
     * @param Location $datatype
     * @return string
     */
    private function getJsonValue($datatype) {
        $value = new \stdClass();

        if(!empty($datatype->getValue())) {
            $value->latitude = $datatype->getValue();
        }
        if(!empty($datatype->getLongitudeElement()->getValue())) {
            $value->longitude = $datatype->getLongitudeElement()->getValue();
        }

        if($datatype->getEngine() === $datatype::ENGINE_GOOGLE) {
            if ($datatype->getGooglePlaceIdElement()) {
                $value->googlePlaceId = $datatype->getGooglePlaceIdElement()->getValue();
            }
        }

        return json_encode($value);
    }
}