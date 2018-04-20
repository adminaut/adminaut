<?php
namespace Adminaut\Widget;
use Adminaut\Form\Form;

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
     * @var Form
     */
    protected static $form;

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

    /**
     * @return Form
     */
    public static function getForm()
    {
        return self::$form;
    }

    /**
     * @param Form $form
     */
    public static function setForm($form)
    {
        self::$form = $form;
    }

    /**
     * @return object
     */
    public function getEntity() {
        return self::$form->getObject();
    }
}