<?php

namespace Adminaut\Form;

use Zend\Form\Element\Button;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Form;

/**
 * Class UserLoginForm
 * @package Adminaut\Form
 */
class UserLoginForm extends Form
{

    /**
     * UserLoginForm constructor.
     */
    public function __construct()
    {

        parent::__construct('UserLogin');

        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => _('Email'),
                'add-on-append' => '<i class="fa fa-user"></i>',
            ],
            'attributes' => [
                'placeholder' => _('Email'),
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => _('Password'),
                'add-on-append' => '<i class="fa fa-key"></i>',
            ],
            'attributes' => [
                'placeholder' => _('Password'),
                'type' => 'password',
            ],
        ]);

        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fa fa-unlock"></i> ' . _('Sign in'),
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary btn-flat pull-right',
            ],
        ]);
    }
}
