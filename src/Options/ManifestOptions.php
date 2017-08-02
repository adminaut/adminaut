<?php

namespace Adminaut\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ManifestOptions
 * @package Adminaut\Options
 */
class ManifestOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    private $name = 'Adminaut';

    /**
     * @var string
     */
    private $description = 'Adminaut - universal automatic administration system';

    /**
     * @var string
     */
    private $display = 'standalone';

    /**
     * @var string
     */
    private $colorTheme = '#3c8dbc';

    /**
     * @var string
     */
    private $colorBackground = '#3c8dbc';

    /**
     * @var array
     */
    private $icons = [
        [
            "src" => "/static/favicons/android-chrome-36x36.png",
            "sizes" => "36x36",
            "type" => "image/png",
            "density" => "0.75",
        ],
    ];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param string $display
     */
    public function setDisplay($display)
    {
        $this->display = (string)$display;
    }

    /**
     * @return string
     */
    public function getColorTheme()
    {
        return $this->colorTheme;
    }

    /**
     * @param string $colorTheme
     */
    public function setColorTheme($colorTheme)
    {
        $this->colorTheme = (string)$colorTheme;
    }

    /**
     * @return string
     */
    public function getColorBackground()
    {
        return $this->colorBackground;
    }

    /**
     * @param string $colorBackground
     */
    public function setColorBackground($colorBackground)
    {
        $this->colorBackground = (string)$colorBackground;
    }

    /**
     * @return array
     */
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     * @param array $icons
     */
    public function setIcons(array $icons)
    {
        $this->icons = $icons;
    }

    /**
     * @param $themeColor
     * @deprecated Use color_theme key.
     */
    public function setThemeColor($themeColor)
    {
        $this->setColorTheme($themeColor);
    }

    /**
     * @param $backgroundColor
     * @deprecated Use color_background key.
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->setColorBackground($backgroundColor);
    }
}
