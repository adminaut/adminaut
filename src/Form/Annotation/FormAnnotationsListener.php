<?php
namespace Adminaut\Form\Annotation;

use \Zend\Form\Annotation\FormAnnotationsListener as ZendFormAnnotationsListener;
use Zend\EventManager\EventManagerInterface;

class FormAnnotationsListener extends ZendFormAnnotationsListener
{
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
                $widgets[] = new $widgetClass;
            }
        }

        $formSpec['widgets'] = $widgets;
    }
}