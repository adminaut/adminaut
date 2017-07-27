<?php

namespace Adminaut\Datatype;
use Traversable;

/**
 * Class Text
 * @package Adminaut\Datatype
 */
class Text extends \Zend\Form\Element\Text
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }


    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'text',
    ];

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
}
