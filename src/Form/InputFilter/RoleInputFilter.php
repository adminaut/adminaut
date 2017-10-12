<?php

namespace Adminaut\Form\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

/**
 * Class RoleInputFilter
 * @package Adminaut\Form\InputFilter
 */
class RoleInputFilter extends InputFilter
{
    /**
     * RoleInputFilter constructor.
     */
    public function __construct()
    {

        $this->add([
            'name' => 'name',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 2,
                        'max' => 32,
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
