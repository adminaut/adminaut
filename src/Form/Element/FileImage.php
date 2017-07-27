<?php

namespace Adminaut\Form\Element;

use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\File\IsImage;
use Zend\Validator\ValidatorInterface;

/**
 * Class FileImage
 * @package Adminaut\Form\Element
 */
class FileImage extends File implements InputProviderInterface
{

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);
        $this->setAttribute('accept', 'image/*');
    }

    /**
     * Get validator
     *
     * @return \Zend\Validator\ValidatorInterface
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new IsImage();
        }
        return $this->validator;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type' => 'Zend\InputFilter\FileInput',
            'name' => $this->getName(),
            'required' => false,
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }
}
