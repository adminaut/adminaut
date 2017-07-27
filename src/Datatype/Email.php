<?php

namespace Adminaut\Datatype;

/**
 * Class Email
 * @package Adminaut\Datatype
 */
class Email extends \Zend\Form\Element\Email
{
    use Datatype {
        setOptions as datatypeSetOptions;
    }

    /**
     * @var string
     */
    protected $icon = 'fa fa-envelope';

    /**
     * @var boolean
     */
    protected $showIcon = true;

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return bool
     */
    public function isShowIcon()
    {
        return $this->showIcon;
    }

    /**
     * @param bool $showIcon
     */
    public function setShowIcon($showIcon)
    {
        $this->showIcon = $showIcon;
    }

    /**
     * @param array|\Traversable $options
     * @return \Zend\Form\Element
     */
    public function setOptions($options)
    {
        if (isset($options['icon'])) {
            $this->setIcon($options['icon']);
        }

        if (isset($options['showIcon'])) {
            $this->setShowIcon($options['showIcon']);
        }

        return $this->datatypeSetOptions($options);
    }
}
