<?php
namespace Adminaut\Widget;

/**
 * Class AbstractWidget
 * @package Adminaut\Widget
 */
abstract class AbstractWidget implements WidgetInterface
{
    /**
     * @var string
     */
    protected $title = "";

    /**
     * @var string|null
     */
    protected $icon;

    /**
     * @var string
     */
    protected $color;

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string|null $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $color
     * @return \InvalidArgumentException
     */
    public function setColor(string $color)
    {
        if(!in_array($string, ['primary', 'success', 'warning', 'danger'])) {
            return new \InvalidArgumentException('Color can be only primary, success, warning or danger');
        }

        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function renderBody()
    {
        return '';
    }

    /**
     * @return string
     */
    public function renderFooter()
    {
        return '';
    }
}