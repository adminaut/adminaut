<?php

namespace Adminaut\Datatype;

/**
 * Class MultiCheckbox
 * @package Adminaut\Datatype
 */
class MultiCheckbox extends \Zend\Form\Element\MultiCheckbox
{
    protected $attributes = [
        'type' => 'datatypeMultiCheckbox',
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
        $values = [];

        foreach ($this->getValue() as $key) {

            if (null === $key) {
                continue;
            }

            $key = (string) $key;

            $valueOptions = $this->getValueOptions();

            if (array_key_exists((string) $key, $valueOptions)) {
                $values[] = $valueOptions[$key];
            }

            foreach ($valueOptions as $valueOption) {
                if (is_array($valueOption) && array_key_exists('value', $valueOption)) {
                    if($key === $valueOption['value'] && array_key_exists('label', $valueOption)) {
                        $values[] = $valueOption['label'];
                    }
                }
            }
        }

        return implode(', ', $values);
    }

    public function setValue($value)
    {
        $this->value = $value === "" ? [] : $value;
        return $this;
    }
}
