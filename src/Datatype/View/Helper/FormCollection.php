<?php

namespace Adminaut\Datatype\View\Helper;

use Adminaut\Datatype\GoogleMap;
use Adminaut\Datatype\GoogleStreetView;
use Adminaut\Datatype\Location;
use TwbBundle\Form\View\Helper\TwbBundleFormCollection;
use Zend\Form\ElementInterface;

/**
 * Class FormCollection
 * @package Adminaut\Datatype\View\Helper
 */
class FormCollection extends TwbBundleFormCollection
{
    /**
     * @param ElementInterface $oElement
     * @return string
     */
    public function render(ElementInterface $oElement)
    {
        if ($oElement instanceof \IteratorAggregate) {
            foreach ($oElement->getIterator() as $oElementOrFieldset) {
                if ($oElementOrFieldset instanceof Location) {
                    if ($oElementOrFieldset->getLongitudeProperty()) {
                        if (isset($oElement->getElements()[$oElementOrFieldset->getLongitudeProperty()])) {
                            $oElementOrFieldset->setLongitudeElement($oElement->getElements()[$oElementOrFieldset->getLongitudeProperty()]);
                            $oElement->remove($oElementOrFieldset->getLongitudeProperty());
                        }
                    }

                    if ($oElementOrFieldset->getGooglePlaceIdProperty()) {
                        if (isset($oElement->getElements()[$oElementOrFieldset->getGooglePlaceIdProperty()])) {
                            $oElementOrFieldset->setGooglePlaceIdElement($oElement->getElements()[$oElementOrFieldset->getGooglePlaceIdProperty()]);
                        }
                    }
                }

                if ($oElementOrFieldset instanceof GoogleMap) {
                    if ($oElementOrFieldset->getLongitudeVariable()) {
                        if (isset($oElement->getElements()[$oElementOrFieldset->getLongitudeVariable()])) {
                            $oElementOrFieldset->setConnectedElement($oElement->getElements()[$oElementOrFieldset->getLongitudeVariable()]);
                            $oElement->remove($oElementOrFieldset->getLongitudeVariable());
                        }
                    }
                }

                if ($oElementOrFieldset instanceof GoogleStreetView) {
                    if ($oElementOrFieldset->getLocationProperty()) {
                        if (isset($oElement->getElements()[$oElementOrFieldset->getLocationProperty()])) {
                            $oElementOrFieldset->setLocationElement($oElement->getElements()[$oElementOrFieldset->getLocationProperty()]);
                        }
                    }
                }
            }
        }

        return parent::render($oElement);
    }
}
