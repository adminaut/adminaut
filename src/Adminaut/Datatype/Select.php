<?php

namespace Adminaut\Datatype;
use Traversable;

/**
 * Class Select
 * @package Adminaut\Datatype
 */
class Select extends \Zend\Form\Element\Select
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    protected $attributes = [
        'type' => 'datatypeSelect',
    ];

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        if($this->getValue() !== null) {
            if(isset($this->getValueOptions()[$this->getValue()])) {
                return $this->getValueOptions()[$this->getValue()];
            }
            return "";
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getInsertValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getEditValue()
    {
        return $this->getValue();
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
     * @param array|Traversable $options
     * @return \Zend\Form\Element
     */
    public function setOptions($options) {
        return $this->datatypeSetOptions($options);
    }
}
