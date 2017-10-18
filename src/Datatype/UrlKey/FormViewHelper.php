<?php

namespace Adminaut\Datatype\UrlKey;

use Adminaut\Datatype\DatatypeHelperTrait;
use Adminaut\Datatype\DateTime;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement as ZendFormElement;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\UrlKey
 */
class FormViewHelper extends ZendFormElement
{
    use DatatypeHelperTrait;

    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param DateTime|ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $element->setAttribute('type', 'text');
        $this->appendScript('adminaut/js/datatype/url-key.js');

        return parent::render($element);
    }
}
