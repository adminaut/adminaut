<?php

namespace Adminaut\Form\Element;

use Adminaut\Form\Element\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

/**
 * Class Time
 * @package Adminaut\Form\Element
 */
class Time extends DateTimeElement
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

    /**
     * @return DateStepValidator
     */
    protected function getStepValidator()
    {
        $format = $this->getFormat();
        $stepValue = (isset($this->attributes['step'])) ? $this->attributes['step'] : 1;
        $baseValue = (isset($this->attributes['min'])) ? $this->attributes['min'] : date($format);
        return new DateStepValidator([
            'format' => $format,
            'baseValue' => $baseValue,
            'timezone' => new \DateTimezone('UTC'),
            'step' => new \DateInterval("PT{$stepValue}S"),
        ]);
    }
}
