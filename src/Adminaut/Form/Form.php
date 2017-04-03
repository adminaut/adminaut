<?php
namespace Adminaut\Form;

use Adminaut\Datatype\Checkbox;
use Adminaut\Datatype\Radio;
use Zend\Form\FieldsetInterface;

/**
 * Class Form
 * @package Adminaut\Form
 */
class Form extends \Zend\Form\Form
{
    /**
     * @var array
     */
    protected $tabs = [
        'main' => [
            'label' => "Main sheet",
            'action' => 'updateAction',
            'active' => false
        ]
    ];

    /**
     * @return array
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @param array $tabs
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;
    }

    /**
     * @param $name
     * @param $data
     */
    public function addTab($name, $data)
    {
        $this->tabs[$name] = $data;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function prepare()
    {
        /** @var ElementInterface | FieldsetInterface $elementOrFieldset */
        foreach ($this->getIterator() as $elementOrFieldset) {
            $elementOrFieldset->setOption('twb-layout', 'horizontal');
            $elementOrFieldset->setOption('column-size', 'sm-10');

            if ($elementOrFieldset->getLabel()) {
                $elementOrFieldset->setLabelAttributes(['class' => 'col-sm-2']);
            } else {
                $elementOrFieldset->setOption('column-size', 'sm-10 col-sm-offset-2');
            }

            if ($elementOrFieldset instanceof Checkbox) {
                $elementOrFieldset->setOption('column-size', 'sm-10 checkbox');
            }

            if ($elementOrFieldset instanceof Radio) {
                $elementOrFieldset->setOption('column-size', 'sm-10 radio');
            }
        }
        return parent::prepare();
    }
}
