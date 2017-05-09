<?php
namespace Adminaut\Form\Element;


use Adminaut\Form\Element;

class CyclicSheet extends Element
{
    protected $target_class;

    protected $referencedProperty = "parentId";


    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($this->options['target_class'])) {
            $this->setTargetClass($this->options['target_class']);
        }

        if (isset($this->options['referenced_property'])) {
            $this->setReferencedProperty($this->options['referenced_property']);
        }
    }

    /**
     * @return mixed
     */
    public function getTargetClass()
    {
        return $this->target_class;
    }

    /**
     * @param mixed $target_class
     */
    public function setTargetClass($target_class)
    {
        $this->target_class = $target_class;
    }

    /**
     * @return string
     */
    public function getReferencedProperty()
    {
        return $this->referencedProperty;
    }

    /**
     * @param string $referencedProperty
     */
    public function setReferencedProperty($referencedProperty)
    {
        $this->referencedProperty = $referencedProperty;
    }
}