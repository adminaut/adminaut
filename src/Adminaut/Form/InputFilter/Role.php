<?php

namespace Adminaut\Form\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class Role
 * @package Adminaut\Form\InputFilter
 */
class Role extends InputFilter
{
    /**
     * Role constructor.
     */
    public function __construct()
	{
        $this->add([
            'name' => 'name',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 32
                    ],
                ],
            ],
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);
	}
}