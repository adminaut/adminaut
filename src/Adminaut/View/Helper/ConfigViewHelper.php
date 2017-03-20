<?php
namespace Adminaut\View\Helper;


use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;

class ConfigViewHelper extends AbstractHelper
{
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function __invoke() {
        return $this->config;
    }
}