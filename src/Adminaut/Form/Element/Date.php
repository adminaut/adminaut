<?php

namespace Adminaut\Form\Element;

use Adminaut\Form\Element\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

/**
 * Class Date
 * @package Adminaut\Form\Element
 */
class Date extends DateTimeElement
{
    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datetime',
    ];

    /**
     * @var string
     */
    protected $format = 'Y-m-d';

    /**
     * @return DateStepValidator
     */
    protected function getStepValidator()
    {
        $format    = $this->getFormat();
        $stepValue = (isset($this->attributes['step'])) ? $this->attributes['step'] : 1;
        $baseValue = (isset($this->attributes['min'])) ? $this->attributes['min'] : date($format);
        return new DateStepValidator([
            'format'    => $format,
            'baseValue' => $baseValue,
            'timezone'  => new \DateTimezone('UTC'),
            'step'      => new \DateInterval("P{$stepValue}D"),
        ]);
    }
}
