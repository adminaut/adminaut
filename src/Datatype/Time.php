<?php

namespace Adminaut\Datatype;

/**
 * Class Time
 * @package Adminaut\Datatype
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
        $options['add-on-append'] = '<i class="fa fa-fw fa-clock-o"></i>';
        parent::setOptions($options);
        return $this;
    }
}
