<?php
namespace Adminaut\Form;

use Zend\Form\Element as ZendFormElement;

/***
 * Class Element
 * @package Adminaut\Form
 */
class Element extends ZendFormElement implements ElementInterface
{
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
    public function getInsertedValue()
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