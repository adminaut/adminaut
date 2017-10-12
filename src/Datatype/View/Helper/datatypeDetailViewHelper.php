<?php

namespace Adminaut\Datatype\View\Helper;

use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class datatypeDetailViewHelper
 * @package Adminaut\Datatype\View\Helper
 */
class datatypeDetailViewHelper extends AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param Element $datatype
     * @return string
     */
    public function render($datatype)
    {
        $pluginName = $datatype->getAttribute('type') . 'Detail';
        $datatypeRenderPlugin = null;

        try {
            $datatypeRenderPlugin = $this->view->plugin($pluginName);
            return $datatypeRenderPlugin->render($datatype);
        } catch (\Exception $e) {
            if (method_exists($datatype, 'getListedValue')) {
                return $datatype->getListedValue();
            }
            return $datatype->getValue();
        }
    }
}
