<?php

namespace Adminaut\Form;

use Zend\Form\Element\Button;
use Zend\Form\Element\Password;
use Zend\Form\Form;

/**
 * Class UserLoginChangePasswordForm
 * @package Adminaut\Form
 */
class UserLoginChangePasswordForm extends Form
{
    /**
     * UserLoginChangePasswordForm constructor.
     */
    public function __construct()
    {

        parent::__construct('UserLoginChangePassword');

        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => _('New password'),
            ],
            'attributes' => [
                'placeholder' => _('New password'),
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'passwordAgain',
            'options' => [
                'label' => _('New password again'),
            ],
            'attributes' => [
                'placeholder' => _('New password again'),
            ],
        ]);

        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => _('Continue'),
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
