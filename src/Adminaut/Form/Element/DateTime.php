<?php

namespace Adminaut\Form\Element;

use DateInterval;
use DateTime as PhpDateTime;

use Adminaut\Form\Element as MfccAdminFormElement;

use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Date as DateValidator;
use Zend\Validator\DateStep as DateStepValidator;
use Zend\Validator\GreaterThan as GreaterThanValidator;
use Zend\Validator\LessThan as LessThanValidator;

/**
 * Class DateTime
 * @package Adminaut\Form\Element
 */
class DateTime extends MfccAdminFormElement implements InputProviderInterface
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
    protected $format = 'Y-m-d H:i:s';

    /**
     * @var array
     */
    protected $validators;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (!isset($options['add-on-append'])) {
            $options['add-on-append'] = '<i class="fa fa-calendar"></i>';
        }
        if (!isset($options['format'])) {
            $options['format'] = $this->getFormat();
        }
        $this->setFormat($options['format']);
        parent::setOptions($options);
        return $this;
    }

    /**
     * @param bool|true $returnFormattedValue
     * @return mixed|string
     */
    public function getValue($returnFormattedValue = true)
    {
        $value = parent::getValue();
        if($value === null || (gettype($value) == "object" && ($value->getTimestamp() === false || $value->getTimestamp() === -3600))) {
            return date($this->getFormat());
        }
        if (!$value instanceof \DateTime || !$returnFormattedValue) {
            //$value = new \DateTime($value);
            return $value;
        }
        $format = $this->getFormat();
        return $value->format($format);
    }

    /**
     * @return PhpDateTime
     */
    public function getInsertedValue()
    {
        return new \DateTime(parent::getInsertedValue());
    }

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        $value = new \DateTime(parent::getListedValue());
        return $value->format($this->getFormat());
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return array
     */
    protected function getValidators()
    {
        if ($this->validators) {
            return $this->validators;
        }

        $validators = [];
        $validators[] = $this->getDateValidator();

        if (isset($this->attributes['min'])) {
            $validators[] = new GreaterThanValidator([
                'min'       => $this->attributes['min'],
                'inclusive' => true,
            ]);
        }
        if (isset($this->attributes['max'])) {
            $validators[] = new LessThanValidator([
                'max'       => $this->attributes['max'],
                'inclusive' => true,
            ]);
        }
        if (!isset($this->attributes['step'])
            || 'any' !== $this->attributes['step']
        ) {
            $validators[] = $this->getStepValidator();
        }

        $this->validators = $validators;
        return $this->validators;
    }

    /**
     * @return DateValidator
     */
    protected function getDateValidator()
    {
        return new DateValidator(['format' => $this->getFormat()]);
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
            'format'    => $format,
            'baseValue' => $baseValue,
            'step'      => new DateInterval("PT{$stepValue}S"),
        ]);
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim'
                ],
            ],
            'validators' => $this->getValidators(),
        ];
    }
}