<?php
namespace Adminaut\Datatype\Location;

use Adminaut\Datatype\Location;
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

        if(!$datatype->getLongitudeElement()) {
            throw new Location\Exception\LongitudeElementNotFound('Longitude element was not found.');
        }

        $identifier = 'datatype-location-' . $datatype->getName();
        $value = method_exists($datatype, 'getEditValue') ? $datatype->getEditValue() : $datatype->getValue();

        $attributes = $datatype->getAttributes();
        unset($attributes['type']);
        if($datatype->getLongitudeElement()) {
            $attributes['data-longitude-element'] = true;
            $attributes['data-longitude-element-name'] = $datatype->getLongitudeElement()->getName();
        }

        if($datatype->getGooglePlaceIdElement()) {
            $attributes['data-google-place-id-element'] = true;
            $attributes['data-google-place-id-element-name'] = $datatype->getGooglePlaceIdElement()->getName();
        }

        if($datatype->getDefaultCenter()) {
            $attributes['data-default-center'] = json_encode($datatype->getDefaultCenter());
        }

        if($datatype->getDefaultZoomLevel()) {
            $attributes['data-default-zoom-level'] = $datatype->getDefaultZoomLevel();
        }

        if($datatype->isEnableDownloadData()) {
            $attributes['data-enable-download-data'] = true;

            if($datatype->getDownloadDataFrom()) {
                $attributes['data-download-data-from'] = json_encode($datatype->getDownloadDataFrom());
            } else {
                throw new \Exception('Elements for download data missing while downloading enabled.');
            }
        }

        $attributes['data-value'] = $this->getJsonValue($datatype);

        $sRender = '<div class="datatype-location" '.$this->createAttributesString($attributes).'>';
        $sRender .= '&#9;<input type="'. ($datatype->isUseHiddenElement() ? 'hidden' : 'text') .'" name="'. $datatype->getName() .'" value="'. $value .'"' . ($datatype->getAttribute('placeholder') ? 'placeholder="'. $datatype->getAttribute('placeholder') .'"' : '') . ' />';
        $sRender .= '&#9;<input type="' . ($datatype->isUseHiddenElement() ? 'hidden' : 'text') . '" name="' . $datatype->getLongitudeElement()->getName() . '" value="' . $datatype->getLongitudeElement()->getValue() . '"' . ($datatype->getLongitudeElement()->getAttribute('placeholder') ? 'placeholder="' . $datatype->getLongitudeElement()->getAttribute('placeholder') . '"' : '') . ' />';

        $sRender .= '    <div class="datatype-location-search-container">';
        $sRender .= '        <input class="controls search-input" type="text" placeholder="'. $this->view->translate('Enter a location') .'">';
        if($datatype->isEnableDownloadData()) {
            $sRender .= '        <button class="gm-button download-data-button" type="button"><i class="fa fa-level-down"></i></button>';
        }
        $sRender .= '        <button class="gm-button remove-data-button" type="button"><i class="fa fa-close"></i></button>';
        $sRender .= '    </div>';
        $sRender .= '</div>';

        $sRender .= '<script>appendScript("'. $this->getView()->basepath('adminaut/js/datatype/location.js') .'")</script>';

        return $sRender;
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
            if ($datatype->getGooglePlaceIdElement() && !empty($datatype->getGooglePlaceIdElement()->getValue())) {
                $value->googlePlaceId = $datatype->getGooglePlaceIdElement()->getValue();
            }
        }

        return json_encode($value);
    }
}