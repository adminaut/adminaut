<?php

namespace Adminaut\Datatype;

use Zend\Form\Element;

/**
 * Class GoogleMap
 * @package Adminaut\Datatype
 */
class GoogleMap extends Element
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var null|string
     */
    protected $longitudeVariable;

    /**
     * @var Element|null
     */
    protected $connectedElement;

    /**
     * @var bool
     */
    protected $useHiddenElement = false;

    /**
     * @var bool
     */
    protected $useJSON = true;

    /**
     * @var string
     */
    protected $separator = ';';

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeGoogleMap',
    ];

    /**
     * @param  array|\Traversable $options
     * @return self
     */
    public function setOptions($options)
    {
        if(isset($options['longVar'])) {
            $this->setLongitudeVariable($options['longVar']);
        } else {
            if (isset($options['use_json'])) {
                $this->setUseJSON($options['use_json']);
            }

            if (isset($options['separator'])) {
                $this->setSeparator($options['separator']);
            }
        }

        if (isset($options['use_hidden_element'])) {
            $this->setUseHiddenElement($options['use_hidden_element']);
        }

        $this->datatypeSetOptions($options);
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLongitudeVariable()
    {
        return $this->longitudeVariable;
    }

    /**
     * @param null|string $longitudeVariable
     */
    public function setLongitudeVariable($longitudeVariable)
    {
        $this->longitudeVariable = $longitudeVariable;
    }

    /**
     * @return null|Element
     */
    public function getConnectedElement()
    {
        return $this->connectedElement;
    }

    /**
     * @param null|Element $connectedElement
     */
    public function setConnectedElement($connectedElement)
    {
        $this->connectedElement = $connectedElement;
    }

    /**
     * @return bool
     */
    public function isUseHiddenElement()
    {
        return $this->useHiddenElement;
    }

    /**
     * @param bool $useHiddenElement
     */
    public function setUseHiddenElement($useHiddenElement)
    {
        $this->useHiddenElement = $useHiddenElement;
    }

    /**
     * @return bool
     */
    public function isUseJSON()
    {
        return $this->useJSON;
    }

    /**
     * @param bool $useJSON
     */
    public function setUseJSON($useJSON)
    {
        $this->useJSON = $useJSON;
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = $this->attributes['name'];
        return $this->attributes;
    }

    public function getEditValue()
    {
        return htmlspecialchars($this->getValue());
    }
}
