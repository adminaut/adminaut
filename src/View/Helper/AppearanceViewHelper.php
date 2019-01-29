<?php

namespace Adminaut\View\Helper;

use Adminaut\Options\AppearanceOptions;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AppearanceViewHelper
 * @package Adminaut\View\Helper
 */
class AppearanceViewHelper extends AbstractHelper
{
    /**
     * @var AppearanceOptions
     */
    private $appearanceOptions;

    /**
     * AppearanceViewHelper constructor.
     * @param AppearanceOptions $appearanceOptions
     */
    public function __construct(AppearanceOptions $appearanceOptions)
    {
        $this->appearanceOptions = $appearanceOptions;
    }

    /**
     * @param $key
     * @return array|null|string
     */
    public function __invoke($key)
    {
        switch ($key) {
            case 'title':
                return $this->appearanceOptions->getTitle();
            case 'description':
                return $this->appearanceOptions->getDescription();
            case 'footer':
                return $this->appearanceOptions->getFooter();
            case 'skin':
                return $this->appearanceOptions->getSkin();
            case 'skin_file':
                return $this->appearanceOptions->getSkinFile();
            case 'logo':
                return $this->appearanceOptions->getLogo();
            case 'logo-large':
                return isset($this->appearanceOptions->getLogo()['large']) ? $this->appearanceOptions->getLogo()['large'] : null;
            case 'logo-small':
                return isset($this->appearanceOptions->getLogo()['small']) ? $this->appearanceOptions->getLogo()['small'] : null;
            case 'logo-type':
                return isset($this->appearanceOptions->getLogo()['type']) ? $this->appearanceOptions->getLogo()['type'] : null;
            case 'theme-color':
                return $this->appearanceOptions->getThemeColor();
            case 'icons':
                return $this->appearanceOptions->getIcons();
            default:
                return null;
        }
    }
}
