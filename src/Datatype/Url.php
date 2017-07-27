<?php

namespace Adminaut\Datatype;

use Zend\Filter\StringTrim;

/**
 * Class Url
 * @package Adminaut\Datatype
 */
class Url extends \Zend\Form\Element\Url
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'url',
    ];

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = $this->attributes['name'];
        return $this->attributes;
    }

    /**
     * Provide default input rules for this element
     *
     * Attaches an uri validator.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                [
                    'name' => StringTrim::class,
                ],
            ],
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }
}
