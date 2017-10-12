<?php

namespace Adminaut\Form;

use Zend\Form\Element\Password;

/**
 * Class UserChangePasswordForm
 * @package Adminaut\Form
 */
class UserChangePasswordForm extends Form
{

    /**
     * UserChangePasswordForm constructor.
     */
    public function __construct()
    {

        parent::__construct('UserChangePassword');

        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => _('Current password'),
            ],
            'attributes' => [
                'placeholder' => _('Current password'),
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'newPassword',
            'options' => [
                'label' => _('New password'),
            ],
            'attributes' => [
                'placeholder' => _('New password'),
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'newPasswordAgain',
            'options' => [
                'label' => _('New password again'),
            ],
            'attributes' => [
                'placeholder' => _('New password again'),
            ],
        ]);
    }
}
