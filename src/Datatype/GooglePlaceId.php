<?php

namespace Adminaut\Datatype;

use Zend\Form\Element;

/**
 * Class GooglePlaceId
 * @package Adminaut\Datatype
 */
class GooglePlaceId extends Element
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var bool
     */
    protected $useHiddenElement = false;

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeGooglePlaceId',
    ];

    /**
     * @param  array|\Traversable $options
     * @return self
     */
    public function setOptions($options)
    {
        if (isset($options['use_hidden_element'])) {
            $this->setUseHiddenElement($options['use_hidden_element']);
        }

        $this->datatypeSetOptions($options);
        return $this;
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
     * @return array
     */
    public function getAttributes()
    {
        $this->attributes['id'] = $this->attributes['name'];
        return $this->attributes;
    }
}
