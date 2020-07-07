<?php

namespace Adminaut\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Element\Password;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

/**
 * Class PasswordRecoveryStepTwoForm
 */
class PasswordRecoveryStepTwoForm extends Form
{

    /**
     * PasswordRecoveryStepTwoForm constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct($name = 'PasswordRecoveryStepTwoForm', array $options = [])
    {
        parent::__construct($name, $options);

        $this->setAttribute('method', 'post');

        $this->setInputFilter(new InputFilter());

        //---------------------------------------------------------------------

        $this->add([
            'type' => Password::class,
            'name' => 'newPassword',
            'options' => [
                'label' => _('New password'),
            ],
            'attributes' => [
                'id' => 'newPassword',
                'class' => 'form-control',
            ],
        ]);

        $this->getInputFilter()->add([
            'name' => 'newPassword',
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

        //---------------------------------------------------------------------

        $this->add([
            'type' => Password::class,
            'name' => 'newPasswordAgain',
            'options' => [
                'label' => _('New password again'),
            ],
            'attributes' => [
                'id' => 'newPasswordAgain',
                'class' => 'form-control',
            ],
        ]);

        $this->getInputFilter()->add([
            'name' => 'newPasswordAgain',
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
                        'token' => 'newPassword',
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
