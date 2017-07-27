<?php

namespace Adminaut\Datatype\Checkbox;

use TwbBundle\Form\View\Helper\TwbBundleFormCheckbox;
use Zend\Form\View\Helper\FormRow;
use Zend\Form\View\Helper\FormCheckbox as ZFFormCheckbox;
use Zend\Form\ElementInterface;
use InvalidArgumentException;
use LogicException;
use Zend\Form\Element\Checkbox;
use Zend\Form\View\Helper\FormLabel;

class FormViewHelper extends TwbBundleFormCheckbox
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
        if ($oElement->getOption('disable-twb')) {
            return parent::render($oElement);
        }

        if (!$oElement instanceof Checkbox) {
            throw new InvalidArgumentException(sprintf(
                '%s requires that the element is of type Zend\Form\Element\Checkbox',
                __METHOD__
            ));
        }
        if (($sName = $oElement->getName()) !== 0 && empty($sName)) {
            throw new LogicException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $aAttributes = $oElement->getAttributes();
        $aAttributes['name'] = $sName;
        $aAttributes['type'] = $this->getInputType();
        $aAttributes['value'] = $oElement->getCheckedValue();
        $aAttributes['id'] = $sName;
        $sClosingBracket = $this->getInlineClosingBracket();

        if ($oElement->isChecked()) {
            $aAttributes['checked'] = 'checked';
        }

        // Render checkbox label
        $sCheckboxLabelOpen = $sCheckboxLabelClose = '';
        $sCheckboxLabelContent = $this->getCheckboxLabelContent($oElement);
        if($sCheckboxLabelContent) {
            $oCheckboxLabelHelper = $this->getLabelHelper();
            $sCheckboxLabelOpen = $oCheckboxLabelHelper->openTag(['class' => 'checkbox-label', 'for' => $sName]);
            $sCheckboxLabelClose = $oCheckboxLabelHelper->closeTag();
        }

        // Render checkbox
        $sElementContent = sprintf('<input %s%s', $this->createAttributesString($aAttributes), $sClosingBracket);

        // Add label markup
        $sElementContent .= $sCheckboxLabelOpen
                    . ($sCheckboxLabelContent ? (' ' . $sCheckboxLabelContent) : '')
                    . $sCheckboxLabelClose;

        //Render hidden input
        if ($oElement->useHiddenElement()) {
            $sElementContent = sprintf(
                '<input type="hidden" %s%s',
                $this->createAttributesString(array(
                    'name' => $aAttributes['name'],
                    'value' => $oElement->getUncheckedValue(),
                )),
                $sClosingBracket
            ) . $sElementContent;
        }
        return $sElementContent;
    }
    
    /**
     * @param ElementInterface $oElement
     * @return string
     */
    public function getCheckboxLabelContent(ElementInterface $oElement){
        $sCheckboxLabelContent = $oElement->getCheckboxLabel() ? : '';
        if ($sCheckboxLabelContent) {
            if ($oTranslator = $this->getTranslator()) {
                $sCheckboxLabelContent = $oTranslator->translate($sCheckboxLabelContent, $this->getTranslatorTextDomain());
            }
        }
        return $sCheckboxLabelContent;
    }
}
