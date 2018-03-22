<?php

namespace Adminaut\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

/**
 * Class ViewHelperPlugin
 * @package Adminaut\Controller\Plugin
 */
class ViewHelperPlugin extends AbstractPlugin
{
    /**
     * @var HelperPluginManager
     */
    private $viewHelperManager;

    /**
     * ViewHelperPlugin constructor.
     * @param HelperPluginManager $viewHelperManager
     */
    public function __construct(HelperPluginManager $viewHelperManager)
    {
        $this->viewHelperManager = $viewHelperManager;
    }

    /**
     * @param string $viewHelper
     * @return AbstractHelper
     */
    public function __invoke(string $viewHelper)
    {
        return $this->viewHelperManager->get($viewHelper);
    }
}
