<?php

namespace Adminaut\Datatype;

use DateInterval;
use DateTime as PhpDateTime;
use Zend\Validator\DateStep as DateStepValidator;

/**
 * Class DateTime
 * @package Adminaut\Form\Element
 */
class DateTime extends \Zend\Form\Element\DateTime
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    protected $attributes = [
        'type' => 'datatypeDateTime',
    ];

    /**
     * @var string
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * @var int
     */
    protected $stepping = 1;

    /**
     * @var bool
     */
    protected $useCurrent = false;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (!isset($options['add-on-prepend'])) {
            $options['add-on-prepend'] = '<i class="fa fa-calendar"></i>';
        }

        if (isset($options['stepping'])) {
            $this->setStepping($options['stepping']);
        }

        if (isset($options['useCurrent'])) {
            $this->setUseCurrent($options['useCurrent']);
        }

        $this->datatypeSetOptions($options);

        parent::setOptions($options);
        return $this;
    }

    /**
     * @param bool|true $returnFormattedValue
     * @return mixed|string
     */
    /*public function getValue($returnFormattedValue = true)
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
    }*/

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        if (!$value instanceof \DateTime) {
            $this->value = \DateTime::createFromFormat($this->getFormat(), $value);
        } else {
            $this->value = $value;
        }
        return $this;
    }

    /**
     * @return PhpDateTime
     */
    public function getInsertValue()
    {
        if (!empty($this->getValue())) {
            return \DateTime::createFromFormat($this->getFormat(), $this->getValue());
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getListedValue()
    {
        if ($this->getValue()) {
            if (!$this->getValue() instanceof \DateTime) {
                $value = \DateTime::createFromFormat($this->getFormat(), $this->getValue());
            } else {
                $value = $this->getValue();
            }
            return $value->format($this->getFormat());
        }
        return "";
    }

    public function getEditValue()
    {
        if ($this->getValue()) {
            if (!$this->getValue() instanceof \DateTime) {
                $value = \DateTime::createFromFormat($this->getFormat(), $this->getValue());
            } else {
                $value = $this->getValue();
            }
            return $value->format($this->getFormat());
        } else {
            return "";
        }
    }

    /**
     * @return int
     */
    public function getStepping()
    {
        return $this->stepping;
    }

    /**
     * @param int $stepping
     */
    public function setStepping($stepping)
    {
        $this->stepping = $stepping;
    }

    /**
     * @return bool
     */
    public function isUseCurrent()
    {
        return $this->useCurrent;
    }

    /**
     * @param bool $useCurrent
     */
    public function setUseCurrent($useCurrent)
    {
        $this->useCurrent = $useCurrent;
    }

    /**
     * @return DateStepValidator
     */
    protected function getStepValidator()
    {
        $format = $this->getFormat();
        $stepValue = $this->getStepping();
        $baseValue = (isset($this->attributes['min'])) ? $this->attributes['min'] : date($format);
        return new DateStepValidator([
            'format' => $format,
            'baseValue' => $baseValue,
            'step' => new DateInterval("PT{$stepValue}S"),
        ]);
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