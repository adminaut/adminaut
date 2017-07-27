<?php

namespace Adminaut\Datatype;

/**
 * Class Number
 * @package Adminaut\Datatype
 */
class Number extends \Zend\Form\Element\Number
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }


    /**
     * @return mixed
     */
    public function getListedValue()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getInsertValue()
    {
        return (int) $this->getValue();
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
}
