<?php

namespace Adminaut\Form\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

/**
 * Class User
 * @package Adminaut\Form\InputFilter
 */
class User extends InputFilter
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                [
                    'name' => StringTrim::class,
                ],
            ],
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => EmailAddress::class,
                ],
            ],
            'filters' => [
                [
                    'name' => StringTrim::class,
                ],
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => false,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 6,
                    ],
                ],
            ],
            'filters' => [
                [
                    'name' => StringTrim::class,
                ],
            ],
        ]);
    }
}
