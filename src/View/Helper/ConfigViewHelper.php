<?php

namespace Adminaut\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class ConfigViewHelper
 * @package Adminaut\View\Helper
 */
class ConfigViewHelper extends AbstractHelper
{
    protected $config;

    /**
     * ConfigViewHelper constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getConfig();
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
