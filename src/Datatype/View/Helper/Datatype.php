<?php

namespace Adminaut\Datatype\View\Helper;

use TwbBundle\Form\View\Helper\TwbBundleFormElement;
use TwbBundle\Options\ModuleOptions;

/**
 * Class Datatype
 * @package Adminaut\Datatype\View\Helper
 */
class Datatype extends TwbBundleFormElement
{
    /**
     * Datatype constructor.
     * @param ModuleOptions $options
     */
    public function __construct(ModuleOptions $options)
    {
        $this->addType('datatypeSelect', 'datatypeFormSelect');
        $this->addType('datatypeCheckbox', 'datatypeFormCheckbox');
        $this->addType('datatypeMultiCheckbox', 'datatypeFormMultiCheckbox');
        $this->addType('datatypeReference', 'datatypeFormReference');
        $this->addType('datatypeMultiReference', 'datatypeFormMultiReference');
        $this->addType('datatypeLocation', 'datatypeFormLocation');
        $this->addType('datatypeGoogleMap', 'datatypeFormGoogleMap');
        $this->addType('datatypeGoogleStreetView', 'datatypeFormGoogleStreetView');
        $this->addType('datatypeGooglePlaceId', 'datatypeFormGooglePlaceId');
        $this->addType('datatypeDateTime', 'datatypeFormDateTime');
        $this->addType('datatypeFile', 'datatypeFormFile');
        $this->addType('datatypeTextarea', 'datatypeFormTextarea');

        parent::__construct($options);
    }
}
