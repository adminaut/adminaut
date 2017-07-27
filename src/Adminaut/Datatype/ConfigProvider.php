<?php

namespace Adminaut\Datatype;

/**
 * Class ConfigProvider
 * @package Adminaut\Datatype
 */
class ConfigProvider
{
    /**
     * Return general-purpose zend-i18n configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'view_helpers' => $this->getViewHelperConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories' => [
                'FormElementManager' => DatatypeManagerFactory::class,
            ],
        ];
    }

    /**
     * Return zend-form helper configuration.
     *
     * Obsoletes View\HelperConfig.
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                // form collection
                'formCollection' => View\Helper\FormCollection::class,

                // form helpers
                'datatypeFormSelect' => Select\FormViewHelper::class,
                'datatypeFormCheckbox' => Checkbox\FormViewHelper::class,
                'datatypeFormMultiCheckbox' => MultiCheckbox\FormViewHelper::class,
                'datatypeFormMultiReference' => MultiReference\FormViewHelper::class,
                'datatypeFormLocation' => Location\FormViewHelper::class,
                'datatypeFormGoogleMap' => GoogleMap\FormViewHelper::class,
                'datatypeFormGoogleStreetView' => GoogleStreetView\FormViewHelper::class,
                'datatypeFormGooglePlaceId' => GooglePlaceId\FormViewHelper::class,
                'datatypeFormDateTime' => DateTime\FormViewHelper::class,
                'datatypeFormFile' => File\FormViewHelper::class,
                'datatypeFormTextarea' => Textarea\FormViewHelper::class,

                // detail helpers
                'datatypeDetail' => View\Helper\datatypeDetailViewHelper::class,
                'datatypeLocationDetail' => Location\DetailViewHelper::class,
                'datatypeGoogleMapDetail' => GoogleMap\DetailViewHelper::class,
                'datatypeGoogleStreetViewDetail' => GoogleStreetView\DetailViewHelper::class,
                'datatypeTextareaDetail' => Textarea\DetailViewHelper::class,
            ],
            'aliases' => [
                'formrow' => View\Helper\FormRow::class,
                'form_row' => View\Helper\FormRow::class,
                'formRow' => View\Helper\FormRow::class,
                'FormRow' => View\Helper\FormRow::class,
                'datatype' => View\Helper\Datatype::class,
            ],
            'factories' => [
                View\Helper\FormRow::class => View\Helper\Factory\FormRowFactory::class,
                View\Helper\Datatype::class => View\Helper\Factory\DatatypeFactory::class,

                //form
                'datatypeFormReference' => Reference\Factory\FormViewHelperFactory::class,
            ],
        ];
    }
}
