<?php

namespace Adminaut\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Class UserLogin
 * @package Adminaut\Form
 */
class UserLogin extends Form
{
    /**
     * UserLogin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setName('UserLogin');
        $this->setAttributes([
            'method' => 'post',
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Email',
            'name' => 'identity',
            'options' => [
                'label' => _('Email'),
                'add-on-append' => '<i class="fa fa-user"></i>'
            ],
            'attributes' => [
                'placeholder' => _('Email'),
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Password',
            'name' => 'credential',
            'options' => [
                'label' => _('Password'),
                'add-on-append' => '<i class="fa fa-key"></i>'
            ],
            'attributes' => [
                'placeholder' => _('Password'),
                'type' => 'password',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Button',
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fa fa-unlock"></i> ' . _('Sing in'),
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary btn-flat pull-right'
            ],
        ]);
    }
}