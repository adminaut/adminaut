<?php

namespace Adminaut\Datatype;

/**
 * Class Radio
 * @package Adminaut\Datatype
 */
class Radio extends \Zend\Form\Element\Radio
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        parent::setOptions($options);
        $this->datatypeSetOptions($options);

        return $this;
    }

    /**
     * @return string
     */
    public function getListedValue()
    {
        if (isset($this->getValueOptions()[$this->getValue()])) {
            return $this->getValueOptions()[$this->getValue()];
        }

        return 'Unknown';
    }
}
