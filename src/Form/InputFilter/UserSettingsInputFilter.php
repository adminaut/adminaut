<?php

namespace Adminaut\Form\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;

/**
 * Class UserSettingsInputFilter
 * @package Adminaut\Form\InputFilter
 */
class UserSettingsInputFilter extends InputFilter
{

    /**
     * UserSettingsInputFilter constructor.
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
    }
}
