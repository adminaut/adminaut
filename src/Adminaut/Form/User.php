<?php

namespace Adminaut\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Class User
 * @package Adminaut\Form
 */
class User extends Form
{
    /**
     * @var int
     */

    const STATUS_INSTALL = 0;
    const STATUS_ADD = 1;
    const STATUS_UPDATE = 2;

    /**
     * @var int
     */
    protected $status;

    /**
     * User constructor.
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
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => [
                'label' => _('Email'),
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
            ],
            'attributes' => [
                'placeholder' => _('Password'),
            ],
        ]);

        if ($status != static::STATUS_INSTALL) {
            $this->add([
                'type' => 'Zend\Form\Element\Select',
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
                'type' => 'Adminaut\Form\Element\Checkbox',
                'name' => 'active',
                'options' => [
                    'label' => _('Active'),
                    'checked_value' => 1,
                    'unchecked_value' => 0
                ],
                'attributes' => [
                    'value' => false,
                ]
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