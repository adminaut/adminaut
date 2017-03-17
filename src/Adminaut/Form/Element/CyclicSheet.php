<?php
/**
 * Created by PhpStorm.
 * User: Josef
 * Date: 17.8.2016
 * Time: 10:55
 */

namespace Adminaut\Form\Element;


use Adminaut\Form\Element;

class CyclicSheet extends Element
{
    protected $target_class;


    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($this->options['target_class'])) {
            $this->setTargetClass($this->options['target_class']);
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
}