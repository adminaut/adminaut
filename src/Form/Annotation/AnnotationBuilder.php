<?php

namespace Adminaut\Form\Annotation;

use Adminaut\Form\Factory;
use Zend\Form\Annotation\AnnotationBuilder as ZendAnnotationBuilder;
use Zend\Code\Annotation\AnnotationManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\Annotation\ElementAnnotationsListener;

/**
 * Class AnnotationBuilder
 * @package Adminaut\Form\Annotation
 */
class AnnotationBuilder extends ZendAnnotationBuilder
{
    /**
     * @var Factory
     */
    protected $formFactory;

    public function __construct()
    {
        $this->defaultAnnotations = array_merge($this->defaultAnnotations, [
            Widgets::class
        ]);
    }

    /**
     * Retrieve form factory
     *
     * Lazy-loads the default form factory if none is currently set.
     *
     * @return Factory
     */
    public function getFormFactory()
    {
        if ($this->formFactory) {
            return $this->formFactory;
        }

        $this->formFactory = new Factory();
        return $this->formFactory;
    }

    /**
     * Set annotation manager to use when building form from annotations
     *
     * @param  AnnotationManager $annotationManager
     * @return AnnotationBuilder
     */
    public function setAnnotationManager(AnnotationManager $annotationManager)
    {
        $parser = $this->getAnnotationParser();
        foreach ($this->defaultAnnotations as $annotationName) {
            if(strpos($annotationName, '\\') === false) {
                $class = 'Zend\Form\Annotation' . '\\' . $annotationName;
            } else {
                $class = $annotationName;
            }
            $parser->registerAnnotation($class);
        }
        $annotationManager->attach($parser);
        $this->annotationManager = $annotationManager;
        return $this;
    }

    /**
     * Configure the form specification from annotations
     *
     * @param  AnnotationCollection $annotations
     * @param  ClassReflection $reflection
     * @param  ArrayObject $formSpec
     * @param  ArrayObject $filterSpec
     * @return void
     * @triggers discoverName
     * @triggers configureForm
     */
    protected function configureForm($annotations, $reflection, $formSpec, $filterSpec)
    {
        $name                   = $this->discoverName($annotations, $reflection);
        $formSpec['name']       = $name;
        $formSpec['attributes'] = [];
        $formSpec['elements']   = [];
        $formSpec['fieldsets']  = [];
        $formSpec['widgets']  = [];

        $events = $this->getEventManager();
        foreach ($annotations as $annotation) {
            $events->trigger(__FUNCTION__, $this, [
                'annotation' => $annotation,
                'name'       => $name,
                'formSpec'   => $formSpec,
                'filterSpec' => $filterSpec,
            ]);
        }
    }

    /**
     * Set event manager instance
     *
     * @param  EventManagerInterface $events
     * @return AnnotationBuilder
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([
            __CLASS__,
            get_class($this),
        ]);
        (new ElementAnnotationsListener())->attach($events);
        (new FormAnnotationsListener())->attach($events);
        $this->events = $events;
        return $this;
    }
}
