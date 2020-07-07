<?php

namespace Adminaut\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Element\Email;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

/**
 * Class PasswordRecoveryStepOneForm
 */
class PasswordRecoveryStepOneForm extends Form
{

    /**
     * PasswordRecoveryStepOneForm constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct($name = 'PasswordRecoveryStepOneForm', array $options = [])
    {
        parent::__construct($name, $options);

        $this->setAttribute('method', 'post');

        $this->setInputFilter(new InputFilter());

        //---------------------------------------------------------------------

        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => _('Email'),
                'add-on-append' => '<i class="fa fa-envelope"></i>',
            ],
            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => _('Email'),
            ],
        ]);

        $this->getInputFilter()->add([
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
    }
}
