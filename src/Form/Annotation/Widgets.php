<?php
namespace Adminaut\Form\Annotation;

use Zend\Form\Annotation\AbstractArrayAnnotation;

/**
 * Widgets annotation
 *
 * Expects an array of Adminaut Widgets.
 *
 * @Annotation
 */
class Widgets extends AbstractArrayAnnotation
{
    /**
     * Retrieve the widgets
     *
     * @return null|array
     */
    public function getWidgets()
    {
        return $this->value;
    }
}