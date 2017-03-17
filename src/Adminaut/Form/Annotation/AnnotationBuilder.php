<?php
/**
 * Created by PhpStorm.
 * User: Josef
 * Date: 26.8.2016
 * Time: 11:01
 */

namespace Adminaut\Form\Annotation;


use Adminaut\Form\Factory;

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