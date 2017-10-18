<?php

namespace Adminaut\Datatype;

/**
 * Class Text
 * @package Adminaut\Datatype
 */
class UrlKey extends Text
{
    /**
     * @var string
     */
    protected $target;

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'datatypeUrlKey'
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
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['class'] = 'url-key-input form-control';
        if(!empty($this->getTarget())) {
            $attributes['data-target'] = $this->getTarget();
        }

        return $attributes;
    }
}
