<?php
namespace Adminaut\Form\View\Helper;

use Adminaut\Form\Element\Reference;
use Zend\Form\ElementInterface;
use \Zend\Form\View\Helper\FormSelect as ZendFormSelect;

class FormReference extends ZendFormSelect
{
    /**
     * Render a form <select> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws \Zend\Form\Exception\InvalidArgumentException
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof Reference) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Form\Element\Reference',
                __METHOD__
            ));
        }

        $name   = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new \Zend\Form\Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $options = $element->getValueOptions();

        if (($emptyOption = $element->getEmptyOption()) !== null) {
            $options = ['' => $emptyOption] + $options;
        }

        $attributes = $element->getAttributes();
        $value      = $this->validateMultiValue($element->getValue(), $attributes);

        $attributes['name'] = $name;
        if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
            $attributes['name'] .= '[]';
        }
        $this->validTagAttributes = $this->validSelectAttributes;

        $rendered = sprintf(
            '<select %s>%s</select>',
            $this->createAttributesString($attributes),
            $this->renderOptions($options, $value)
        );

        // Render hidden element
        $useHiddenElement = method_exists($element, 'useHiddenElement')
            && method_exists($element, 'getUnselectedValue')
            && $element->useHiddenElement();

        if ($useHiddenElement) {
            $rendered = $this->renderHiddenElement($element) . $rendered;
        }

        return $rendered;
    }
}