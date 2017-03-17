<?php
/**
 * Created by PhpStorm.
 * User: Josef
 * Date: 17.8.2016
 * Time: 10:50
 */

namespace Adminaut\Form;


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

    public function addTab($name, $data){
        $this->tabs[$name] = $data;
    }
}