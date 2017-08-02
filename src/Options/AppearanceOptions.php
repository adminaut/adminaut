<?php

namespace Adminaut\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AppearanceOptions
 * @package Adminaut\Options
 */
class AppearanceOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    private $skin = 'blue';

    /**
     * @var string
     */
    private $title = 'Adminaut';

    /**
     * @var string
     */
    private $description = 'Adminaut - universal automatic administration system';

    /**
     * @var string
     * todo: add default footer
     */
    private $footer = '';

    /**
     * @var array
     */
    private $logo = [
        'type' => 'image',
        'large' => 'adminaut/img/admin-logo-lg.svg',
        'small' => 'adminaut/img/admin-logo-mini.png',
    ];

    /**
     * @return string
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * @param string $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param string $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return array
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
}
