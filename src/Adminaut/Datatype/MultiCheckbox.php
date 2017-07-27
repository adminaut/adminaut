<?php

namespace Adminaut\Datatype;

/**
 * Class MultiCheckbox
 * @package Adminaut\Datatype
 */
class MultiCheckbox extends \Zend\Form\Element\MultiCheckbox
{
    protected $attributes = [
        'type' => 'datatypeMultiCheckbox'
    ];

    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = $this->attributes['name'];
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getListedValue()
    {
        return implode(',', $this->getValue());
    }

    public function setValue($value)
    {
        $this->value = $value === "" ? [] : $value;
        return $this;
    }


}
