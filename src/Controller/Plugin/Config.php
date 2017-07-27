<?php

namespace Adminaut\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Config
 * @package Adminaut\Controller\Plugin
 */
class Config extends AbstractPlugin
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
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
