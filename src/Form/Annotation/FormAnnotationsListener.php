<?php
namespace Adminaut\Form\Annotation;

use \Zend\Form\Annotation\FormAnnotationsListener as ZendFormAnnotationsListener;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceManager;

class FormAnnotationsListener extends ZendFormAnnotationsListener
{
    /**
     * @var ServiceManager
     */
    private static $serviceManager;

    /**
     * Attach listeners
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        parent::attach($events, $priority);

        $this->listeners[] = $events->attach('configureForm', [$this, 'handleWidgetsAnnotation'], $priority);
    }

    /**
     * Handle the Widgets annotation
     *
     * Sets the widgets key of the form specification.
     *
     * @param  \Zend\EventManager\EventInterface $e
     * @return void
     */
    public function handleWidgetsAnnotation($e)
    {
        $annotation = $e->getParam('annotation');
        if (! $annotation instanceof Widgets) {
            return;
        }

        $formSpec = $e->getParam('formSpec');

        $widgets = array();
        foreach ($annotation->getWidgets() as $widgetClass) {
            if(class_exists($widgetClass)) {
                if(self::$serviceManager->has($widgetClass)) {
                    $widgets[] = self::$serviceManager->get($widgetClass);
                } else {
                    $widgets[] = new $widgetClass;
                }
            }
        }

        $formSpec['widgets'] = $widgets;
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager(): ServiceManager
    {
        return self::$serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }
}