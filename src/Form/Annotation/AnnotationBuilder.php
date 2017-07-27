<?php

namespace Adminaut\Form\Annotation;

use Adminaut\Form\Factory;
use Zend\Form\Annotation\AnnotationBuilder as ZendAnnotationBuilder;

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
