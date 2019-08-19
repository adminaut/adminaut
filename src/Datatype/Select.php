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
     * @return string
     */
    public function getListedValue()
    {
        $key = $this->getValue();

        if (null === $key) {
            return '';
        }

        $key = (string) $key;

        $valueOptions = $this->getValueOptions();

        if (array_key_exists((string) $key, $valueOptions)) {
            return $valueOptions[$key];
        }

        foreach ($valueOptions as $valueOption) {
            if (is_array($valueOption) && array_key_exists('options', $valueOption)) {
                if (array_key_exists($key, $valueOption['options'])) {
                    return $valueOption['options'][$key];
                }
            }
        }

        return '';
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
    public function getFilterValue()
    {
        return [ 'id' => $this->getValue(), 'name' => $this->getListedValue() ];
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
    public function setOptions($options)
    {
        return $this->datatypeSetOptions($options);
    }
}
