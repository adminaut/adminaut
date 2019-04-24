<?php

namespace Adminaut\Datatype\MultiReference;

use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Select;
use Adminaut\Datatype\MultiCheckbox;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormSelect;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\MultiReference
 */
class FormViewHelper extends AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormSelect
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    public function render($datatype)
    {
        if (!$datatype instanceof MultiReference) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\MultiReference',
                __METHOD__
            ));
        }

        if ($datatype->getVisualization() == 'select') {
            $select = new Select();
            $selectViewHelper = $this->getView()->plugin('datatypeFormSelect');
            foreach ($datatype->getObjectVars() as $key => $value) {
                if ($key == 'emptyValue') {
                    $select->setUnselectedValue($value);
                    continue;
                }

                if (method_exists($select, 'set' . ucfirst($key))) {
                    $select->{'set' . ucfirst($key)}($value);
                }
            }

            return $selectViewHelper->render($select);
        } else if ($datatype->getVisualization() == 'checkbox') {
            $multiCheckbox = new MultiCheckbox();
            $multiCheckboxViewHelper = $this->getView()->plugin('datatypeFormMultiCheckbox');
            foreach ($datatype->getObjectVars() as $key => $value) {
                if ($key == 'emptyValue') {
                    $multiCheckbox->setUncheckedValue($value);
                    continue;
                }

                if (method_exists($multiCheckbox, 'set' . ucfirst($key))) {
                    $multiCheckbox->{'set' . ucfirst($key)}($value);
                }
            }

            return $multiCheckboxViewHelper->render($multiCheckbox);
        }
    }
}
