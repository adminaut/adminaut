<?php

namespace Adminaut\Datatype\MultiCheckbox;

use Adminaut\Datatype\MultiCheckbox;
use Zend\Form\Element\MultiCheckbox as MultiCheckboxElement;
use TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox;
use Zend\Form\LabelAwareInterface;
use Zend\Form\View\Helper\FormRow;
use Zend\Form\View\Helper\FormMultiCheckbox as ZFFormMultiCheckbox;
use Zend\Form\ElementInterface;
use InvalidArgumentException;
use LogicException;
use Zend\Form\View\Helper\FormLabel;

class FormViewHelper extends TwbBundleFormMultiCheckbox
{
    /**
     * @see TwbBundleFormCheckbox::render()
     * @param ElementInterface $oElement
     * @throws LogicException
     * @throws InvalidArgumentException
     * @return string
     */
    public function render(ElementInterface $oElement)
    {
        if (! $oElement instanceof MultiCheckbox) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\MultiCheckbox',
                __METHOD__
            ));
        }

        $name = static::getName($oElement);
        $options = $oElement->getValueOptions();

        $attributes         = $oElement->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getInputType();
        $selectedOptions    = (array) $oElement->getValue();

        $rendered = $this->renderOptions($oElement, $options, $selectedOptions, $attributes);

        // Render hidden element
        $useHiddenElement = method_exists($oElement, 'useHiddenElement') && $oElement->useHiddenElement()
            ? $oElement->useHiddenElement()
            : $this->useHiddenElement;

        if ($useHiddenElement) {
            $rendered = $this->renderHiddenElement($oElement, $attributes) . $rendered;
        }

        $aElementOptions = $oElement->getOptions();

        return sprintf('<div class="checkbox">%s</div>', $rendered);
    }

    /**
     * @param MultiCheckbox|MultiCheckboxElement $element
     * @param array $options
     * @param array $selectedOptions
     * @param array $attributes
     * @return string
     */
    protected function renderOptions(
        MultiCheckboxElement $element,
        array $options,
        array $selectedOptions,
        array $attributes
    ) {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper      = $this->getLabelHelper();
        $labelClose       = $labelHelper->closeTag();
        $labelPosition    = $this->getLabelPosition();
        $globalLabelAttributes = [];
        $closingBracket   = $this->getInlineClosingBracket();

        if ($element instanceof LabelAwareInterface) {
            $globalLabelAttributes = $element->getLabelAttributes();
        }

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $combinedMarkup = [];
        $count          = 0;

        foreach ($options as $key => $optionSpec) {
            $count++;

            $value           = '';
            $label           = '';
            $inputAttributes = $attributes;
            $labelAttributes = ['class' => 'checkbox-label', 'for' => $inputAttributes['id']];
            $selected        = (isset($inputAttributes['selected'])
                && $inputAttributes['type'] != 'radio'
                && $inputAttributes['selected']);
            $disabled        = (isset($inputAttributes['disabled']) && $inputAttributes['disabled']);

            if (is_scalar($optionSpec)) {
                $optionSpec = [
                    'label' => $optionSpec,
                    'value' => $key
                ];
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }
            if (isset($optionSpec['label_attributes'])) {
                $labelAttributes = (isset($labelAttributes))
                    ? array_merge($labelAttributes, $optionSpec['label_attributes'])
                    : $optionSpec['label_attributes'];
            }
            if (isset($optionSpec['attributes'])) {
                $inputAttributes = array_merge($inputAttributes, $optionSpec['attributes']);
            }

            if (in_array($value, $selectedOptions)) {
                $selected = true;
            }

            $inputAttributes['id'] .= '-' . $value;
            $inputAttributes['value']    = $value;
            $inputAttributes['checked']  = $selected;
            $inputAttributes['disabled'] = $disabled;

            $input = sprintf(
                '<input %s%s',
                $this->createAttributesString($inputAttributes),
                $closingBracket
            );

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label,
                    $this->getTranslatorTextDomain()
                );
            }

            if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }

            $labelOpen = $labelHelper->openTag($labelAttributes);

            if($element->getOption('inline') == true) {
                $markup = '<div class="form-group col-xs-12 col-sm-4">' . $input;
            } else {
                $markup = '<div class="form-group col-xs-12">' . $input;
            }

            switch ($labelPosition) {
                case self::LABEL_PREPEND:
                    $markup = $labelOpen . $label . $labelClose . $input;
                    break;
                case self::LABEL_APPEND:
                default:
                    $markup .= $labelOpen . $label . $labelClose;
                    break;
            }

            $markup .= '</div>';

            $combinedMarkup[] = $markup;
        }

        return implode($this->getSeparator(), $combinedMarkup);
    }
}
