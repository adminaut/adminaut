<?php

namespace Adminaut;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Adminaut
 */
class Module implements ConfigProviderInterface, BootstrapListenerInterface
{

    /**
     * @param MvcEvent $e
     */
    function onDispatchError(MvcEvent $e)
    {
        $vm = $e->getViewModel();
        $vm->setTemplate('layout/admin-blank');
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface|MvcEvent $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        // todo: do we need this?
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        return [];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
//    public function getFormElementConfig()
//    {
//        return [
//            'invokables' => [
//                // form collection
//                'formCollection' => Datatype\View\Helper\FormCollection::class, // todo: rename to datatypeFormCollection so we don't overwrite default formCollection?
//
//                // form helpers
//                'datatypeFormSelect' => Datatype\Select\FormViewHelper::class,
//                'datatypeFormCheckbox' => Datatype\Checkbox\FormViewHelper::class,
//                'datatypeFormMultiCheckbox' => Datatype\MultiCheckbox\FormViewHelper::class,
//                'datatypeFormMultiReference' => Datatype\MultiReference\FormViewHelper::class,
//                'datatypeFormLocation' => Datatype\Location\FormViewHelper::class,
//                'datatypeFormGoogleMap' => Datatype\GoogleMap\FormViewHelper::class,
//                'datatypeFormGoogleStreetView' => Datatype\GoogleStreetView\FormViewHelper::class,
//                'datatypeFormGooglePlaceId' => Datatype\GooglePlaceId\FormViewHelper::class,
//                'datatypeFormDateTime' => Datatype\DateTime\FormViewHelper::class,
//                'datatypeFormFile' => Datatype\File\FormViewHelper::class,
//                'datatypeFormTextarea' => Datatype\Textarea\FormViewHelper::class,
//
//                // detail helpers
//                'datatypeDetail' => Datatype\View\Helper\datatypeDetailViewHelper::class,
//                'datatypeLocationDetail' => Datatype\Location\DetailViewHelper::class,
//                'datatypeGoogleMapDetail' => Datatype\GoogleMap\DetailViewHelper::class,
//                'datatypeGoogleStreetViewDetail' => Datatype\GoogleStreetView\DetailViewHelper::class,
//                'datatypeTextareaDetail' => Datatype\Textarea\DetailViewHelper::class,
//            ],
//            'aliases' => [
//                'formrow' => Datatype\View\Helper\FormRow::class,
//                'form_row' => Datatype\View\Helper\FormRow::class,
//                'formRow' => Datatype\View\Helper\FormRow::class,
//                'FormRow' => Datatype\View\Helper\FormRow::class,
//                'datatype' => Datatype\View\Helper\Datatype::class,
//            ],
//            'factories' => [
//                Datatype\View\Helper\FormRow::class => Datatype\View\Helper\Factory\FormRowFactory::class,
//                Datatype\View\Helper\Datatype::class => Datatype\View\Helper\Factory\DatatypeFactory::class,
//
//                //form
//                'datatypeFormReference' => Datatype\Reference\Factory\FormViewHelperFactory::class,
//            ],
//        ];
//    }
}
