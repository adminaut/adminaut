<?php

namespace Adminaut\Controller\Plugin;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class TranslatorPlugin
 * @package Adminaut\Controller\Plugin
 */
class TranslatorPlugin extends AbstractPlugin
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * TranslatorPlugin constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Translator
     */
    public function __invoke()
    {
        return $this->translator;
    }
}
