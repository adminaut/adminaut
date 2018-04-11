<?php

namespace Adminaut;

return [
    'invokables' => [
        // form collection
        'formCollection' => Datatype\View\Helper\FormCollection::class, // todo: rename to datatypeFormCollection so we don't overwrite default formCollection?

        // form helpers
        'datatypeFormSelect' => Datatype\Select\FormViewHelper::class,
        'datatypeFormCheckbox' => Datatype\Checkbox\FormViewHelper::class,
        'datatypeFormMultiCheckbox' => Datatype\MultiCheckbox\FormViewHelper::class,
        'datatypeFormMultiReference' => Datatype\MultiReference\FormViewHelper::class,
        'datatypeFormLocation' => Datatype\Location\FormViewHelper::class,
        'datatypeFormGoogleMap' => Datatype\GoogleMap\FormViewHelper::class,
        'datatypeFormGoogleStreetView' => Datatype\GoogleStreetView\FormViewHelper::class,
        'datatypeFormGooglePlaceId' => Datatype\GooglePlaceId\FormViewHelper::class,
        'datatypeFormDateTime' => Datatype\DateTime\FormViewHelper::class,
        'datatypeFormTextarea' => Datatype\Textarea\FormViewHelper::class,
        'datatypeFormSlug' => Datatype\Slug\FormViewHelper::class,
        'datatypeFormColor' => Datatype\Color\FormViewHelper::class,

        // detail helpers
        'datatypeDetail' => Datatype\View\Helper\datatypeDetailViewHelper::class,
        'datatypeLocationDetail' => Datatype\Location\DetailViewHelper::class,
        'datatypeGoogleMapDetail' => Datatype\GoogleMap\DetailViewHelper::class,
        'datatypeGoogleStreetViewDetail' => Datatype\GoogleStreetView\DetailViewHelper::class,

        'formrow' => Datatype\View\Helper\FormRow::class,
        'form_row' => Datatype\View\Helper\FormRow::class,
        'formRow' => Datatype\View\Helper\FormRow::class,
        'FormRow' => Datatype\View\Helper\FormRow::class,

        // Widget
        'widget' => Widget\View\Helper\WidgetViewHelper::class,
    ],
    'factories' => [
        Form\View\Helper\FormElement::class => Form\View\Helper\Factory\FormElementFactory::class,
        View\Helper\UserIdentity::class => View\Helper\Factory\UserIdentityFactory::class,
        View\Helper\IsAllowed::class => View\Helper\Factory\IsAllowedViewHelperFactory::class,
        View\Helper\ConfigViewHelper::class => View\Helper\Factory\ConfigViewHelperFactory::class,
        View\Helper\AppearanceViewHelper::class => View\Helper\Factory\AppearanceViewHelperFactory::class,
        View\Helper\VariableViewHelper::class => View\Helper\Factory\VariableViewHelperFactory::class,
        View\Helper\GetDataTableLanguage::class => View\Helper\Factory\GetDataTableLanguageFactory::class,
        View\Helper\ImageHelper::class => View\Helper\Factory\ImageHelperFactory::class,

        Datatype\File\FormViewHelper::class => Datatype\File\Factory\FormViewHelperFactory::class,
        'formElement' => Form\View\Helper\Factory\FormElementFactory::class,
        'FormElement' => Form\View\Helper\Factory\FormElementFactory::class,
        'form_element' => Form\View\Helper\Factory\FormElementFactory::class,
        'formelement' => Form\View\Helper\Factory\FormElementFactory::class,

        // Datatype helpers
        Datatype\View\Helper\FormRow::class => Datatype\View\Helper\Factory\FormRowFactory::class,
        Datatype\View\Helper\Datatype::class => Datatype\View\Helper\Factory\DatatypeFactory::class,

        Datatype\File\DetailViewHelper::class => Datatype\File\Factory\DetailViewHelperFactory::class,

        'datatypeFormReference' => Datatype\Reference\Factory\FormViewHelperFactory::class,
    ],
    'aliases' => [
        'userIdentity' => View\Helper\UserIdentity::class,
        'isAllowed' => View\Helper\IsAllowed::class,
        'config' => View\Helper\ConfigViewHelper::class,
        'getDataTableLanguage' => View\Helper\GetDataTableLanguage::class,
        'adminautImage' => View\Helper\ImageHelper::class,
        'image' => View\Helper\ImageHelper::class,

        'adminautAppearance' => View\Helper\AppearanceViewHelper::class,
        'adminautVariable' => View\Helper\VariableViewHelper::class,

        'formElement' => Form\View\Helper\FormElement::class,
        'FormElement' => Form\View\Helper\FormElement::class,
        'form_element' => Form\View\Helper\FormElement::class,
        'formelement' => Form\View\Helper\FormElement::class,

        // Datatype helpers
        'formrow' => Datatype\View\Helper\FormRow::class,
        'form_row' => Datatype\View\Helper\FormRow::class,
        'formRow' => Datatype\View\Helper\FormRow::class,
        'FormRow' => Datatype\View\Helper\FormRow::class,
        'datatype' => Datatype\View\Helper\Datatype::class,

        // Datatypes
        'datatypeFormFile' => Datatype\File\FormViewHelper::class,
        'datatypeFileDetail' => Datatype\File\DetailViewHelper::class
    ],
];
