<?php

namespace Adminaut\Datatype;

/**
 * Class Text
 * @package Adminaut\Datatype
 */
class Slug extends Text
{
    /**
     * @var string
     */
    protected $target;

    /**
     * @var boolean
     */
    protected $convertCyrillic = false;

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeSlug'
    ];

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (!isset($options['add-on-prepend'])) {
            $options['add-on-prepend'] = '<i class="fa fa-fw fa-anchor"></i>';
        }

        if (isset($options['target'])) {
            $this->setTarget($options['target']);
        }

        if (isset($options['convert-cyrillic'])) {
            $this->setConvertCyrillic($options['convert-cyrillic']);
        }

        parent::setOptions($options);
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return bool
     */
    public function isConvertCyrillic()
    {
        return $this->convertCyrillic;
    }

    /**
     * @param bool $convertCyrillic
     */
    public function setConvertCyrillic($convertCyrillic)
    {
        $this->convertCyrillic = $convertCyrillic;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['class'] = 'slug-input form-control';

        if(!empty($this->getTarget())) {
            $attributes['data-target'] = $this->getTarget();
        }

        if(!empty($this->isConvertCyrillic())) {
            $attributes['data-convert-cyrillic'] = $this->isConvertCyrillic();
        }

        return $attributes;
    }
}
