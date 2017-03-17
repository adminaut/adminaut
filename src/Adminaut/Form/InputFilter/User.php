<?php

namespace Adminaut\Form\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class User
 * @package Adminaut\Form\InputFilter
 */
class User extends InputFilter
{
    /**
     * UserLogin constructor.
     */
    public function __construct()
	{
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress',
                ],
            ],
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'credential',
            'required' => false,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                    ],
                ],
            ],
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);
	}
}