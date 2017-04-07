<?php
namespace Adminaut\Form\Annotation;


use Adminaut\Form\Factory;
use ArrayObject;
use Zend\Code\Annotation\AnnotationCollection;
use Zend\Code\Reflection\ClassReflection;
use Zend\Stdlib\ArrayUtils;

class AnnotationBuilder extends \Zend\Form\Annotation\AnnotationBuilder
{
    /**
     * @var Factory
     */
    protected $formFactory;

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
}