<?php

namespace Adminaut\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class ConfigPlugin
 * @package Adminaut\Controller\Plugin
 */
class ConfigPlugin extends AbstractPlugin
{
    /**
     * @var array
     */
    private $config;

    /**
     * ConfigPlugin constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->config;
    }
}
