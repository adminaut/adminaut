<?php

namespace Adminaut\Datatype;

use Zend\Validator\DateStep as DateStepValidator;

/**
 * Class Date
 * @package Adminaut\Form\Element
 */
class Time extends DateTime
{
    /**
     * @var string
     */
    protected $format = 'H:i:s';

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        $options['add-on-append'] = '<i class="fa fa-clock-o"></i>';
        parent::setOptions($options);
        return $this;
    }
}
