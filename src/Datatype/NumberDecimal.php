<?php

namespace Adminaut\Datatype;

/**
 * Class NumberDecimal
 * @package Adminaut\Datatype
 */
class NumberDecimal extends \Zend\Form\Element\Number
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var float
     */
    protected $min = 0.00;

    /**
     * @var float
     */
    protected $max = 100.00;

    /**
     * @var float
     */
    protected $step = 0.01;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['min'])) {
            $this->min = (float)$options['min'];
        }
        $this->setAttribute('min', $this->min);

        if (isset($options['max'])) {
            $this->max = (float)$options['max'];
        }
        $this->setAttribute('max', $this->max);

        if (isset($options['step'])) {
            $this->step = (float)$options['step'];
        }
        $this->setAttribute('step', $this->step);

        $this->datatypeSetOptions($options);
        return $this;
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
        return (float)$this->getValue();
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
        $this->setAttribute('id', $this->getAttribute('name'));
        return $this->attributes;
    }
}
