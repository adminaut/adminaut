<?php
namespace Adminaut\Form\Element;


use Adminaut\Form\Element;

class CyclicSheet extends Element
{
    /**
     * @var Object
     */
    protected $target_class;

    /**
     * @var string
     */
    protected $referencedProperty = "parentId";

    /**
     * @var bool
     */
    protected $readonly = false;


    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($this->options['target_class'])) {
            $this->setTargetClass($this->options['target_class']);
        }

        if (isset($this->options['referenced_property'])) {
            $this->setReferencedProperty($this->options['referenced_property']);
        }

        if (isset($this->options['readonly'])) {
            $this->setReadonly($this->options['readonly']);
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

    /**
     * @return bool
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param bool $readonly
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }
}