<?php

namespace Adminaut\Datatype\Reference;

use Adminaut\Datatype\Radio;
use Adminaut\Datatype\Reference;
use Adminaut\Datatype\Select;
use Adminaut\Manager\AdminautModulesManager;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormSelect;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\Reference
 */
class FormViewHelper extends AbstractHelper
{
    /**
     * @var AdminautModulesManager
     */
    protected $adminModulesManager;

    /**
     * FormViewHelper constructor.
     * @param AdminautModulesManager $adminModulesManager
     */
    public function __construct($adminModulesManager)
    {
        $this->setAdminModulesManager($adminModulesManager);
    }

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
        if (!$datatype instanceof Reference) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\Reference',
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

            if ($select->getValue()) {

            }

            $sRender = $selectViewHelper->render($select);
        } else if ($datatype->getVisualization() == 'radio') {
            $radio = new Radio();
            $radioViewHelper = $this->getView()->plugin('formRadio');
            foreach ($datatype->getObjectVars() as $key => $value) {
                if ($key == 'emptyValue') {
                    $radio->setUncheckedValue($value);
                    continue;
                }

                if (method_exists($radio, 'set' . ucfirst($key))) {
                    $radio->{'set' . ucfirst($key)}($value);
                }
            }

            $sRender = $radioViewHelper->render($radio);
        }

        if (!$datatype->isSubEntityReference()) {
            $moduleId = $this->getAdminModulesManager()->getModuleByEntityClass($datatype->getProxy()->getTargetClass());

            if($moduleId) {
                $sRender .= '<p class="help-block">' . sprintf($this->view->translate('New record can be added <a href="%s">here</a>', 'adminaut'), $this->getView()->url('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'add'])) . '</p>';
            }
        }
        return $sRender;
    }

    /**
     * @return AdminautModulesManager
     */
    public function getAdminModulesManager()
    {
        return $this->adminModulesManager;
    }

    /**
     * @param AdminautModulesManager $adminModulesManager
     */
    public function setAdminModulesManager($adminModulesManager)
    {
        $this->adminModulesManager = $adminModulesManager;
    }
}
