<?php

namespace Adminaut\Form\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

/**
 * Class UserLoginInputFilter
 * @package Adminaut\Form\InputFilter
 */
class UserLoginInputFilter extends InputFilter
{

    /**
     * UserLogin constructor.
     */
    public function __construct()
    {
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
            'required' => true,
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
