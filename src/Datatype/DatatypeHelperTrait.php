<?php

namespace Adminaut\Datatype;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\BasePath;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadScript;
use Zend\View\Renderer\PhpRenderer;

/**
 * Trait DatatypeHelperTrait
 * @package Adminaut\Datatype
 */
trait DatatypeHelperTrait
{

    /**
     * @return PhpRenderer
     * @throws \Exception
     */
    public function getPhpRenderer()
    {
        if (false === method_exists($this, 'getView')) {
            throw new \Exception(_('Method getView() does not exist.'));
        }

        $view = $this->getView();

        if (false === $view instanceof PhpRenderer) {
            throw new \Exception(sprintf(_('View is not instance of %s.'), PhpRenderer::class));
        }

        return $view;
    }

    /**
     * @return AbstractHelper|BasePath
     */
    public function getBasePathPlugin()
    {
        return $this->getPhpRenderer()->plugin(BasePath::class);
    }

    /**
     * @return AbstractHelper|HeadScript
     */
    public function getHeadScriptPlugin()
    {
        return $this->getPhpRenderer()->plugin(HeadScript::class);
    }

    /**
     * @return AbstractHelper|HeadLink
     */
    public function getHeadLinkPlugin()
    {
        return $this->getPhpRenderer()->plugin(HeadLink::class);
    }

    /**
     * @param $src
     * @param bool $local
     */
    public function appendStylesheet($src, $local = true)
    {
        if (true === $local) {
            $src = $this->getBasePathPlugin()($src);
        }

        $this->getHeadLinkPlugin()->appendStylesheet($src);
    }

    /**
     * @param $src
     * @param bool $local
     */
    public function appendScript($src, $local = true)
    {
        if (true === $local) {
            $src = $this->getBasePathPlugin()($src);
        }

        $this->getHeadScriptPlugin()->appendFile($src);
    }
}
