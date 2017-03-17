<?php

namespace Adminaut\Form\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class UserLogin
 * @package Adminaut\Form\InputFilter
 */
class UserLogin extends InputFilter
{
    /**
     * UserLogin constructor.
     */
    public function __construct()
	{
        $this->add([
            'name' => 'identity',
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
            'required' => true,
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