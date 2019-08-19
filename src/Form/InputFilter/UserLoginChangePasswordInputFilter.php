<?php

namespace Adminaut\Form\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

/**
 * Class UserLoginChangePasswordInputFilter
 * @package Adminaut\Form\InputFilter
 */
class UserLoginChangePasswordInputFilter extends InputFilter
{

    /**
     * UserLoginChangePasswordInputFilter constructor.
     */
    public function __construct()
    {
        $this->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
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

        $this->add([
            'name' => 'passwordAgain',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 6,
                    ],
                ],
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => 'password',
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
