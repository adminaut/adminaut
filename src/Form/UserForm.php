<?php

namespace Adminaut\Form;

use Adminaut\Form\Element\Checkbox;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class UserForm
 * @package Adminaut\Form
 */
class UserForm extends Form
{

    /**
     * Constants.
     */
    const STATUS_INSTALL = 0;
    const STATUS_ADD = 1;
    const STATUS_UPDATE = 2;

    /**
     * @var int
     */
    protected $status;

    /**
     * UserForm constructor.
     * @param int $status
     * @param array $option
     */
    public function __construct($status = self::STATUS_ADD, array $option = [])
    {

        parent::__construct();

        $this->setStatus($status);

        $this->setName('User');
        $this->setAttributes([
            'method' => 'post',
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'name',
            'options' => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
            ],
        ]);

        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => _('Email'),
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
            ],
            'attributes' => [
                'placeholder' => _('Password'),
            ],
        ]);

        if ($status != static::STATUS_INSTALL) {
            $this->add([
                'type' => Select::class,
                'name' => 'role',
                'attributes' => [
                    'placeholder' => _('Role'),
                ],
                'options' => [
                    'label' => _('Role'),
                    'empty_option' => _('Select role'),
                ],
            ]);

            $this->add([
                'type' => Checkbox::class,
                'name' => 'active',
                'options' => [
                    'label' => _('Active'),
                    'checked_value' => 1,
                    'unchecked_value' => 0,
                ],
                'attributes' => [
                    'value' => false,
                ],
            ]);
        }

        if ($status == static::STATUS_INSTALL) {
            $this->add([
                'type' => 'Zend\Form\Element\Button',
                'name' => 'submit',
                'options' => [
                    'label' => 'Register',
                ],
                'attributes' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary pull-right',
                ],
            ]);
        }
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
