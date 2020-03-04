<?php

namespace Adminaut\Controller\Plugin;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class TranslatePlugin
 * @package Adminaut\Controller\Plugin
 */
class TranslatePlugin extends AbstractPlugin
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * TranslatePlugin constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $message
     * @param string $textDomain
     * @param null $locale
     * @return string
     */
    public function __invoke($message, $textDomain = 'default', $locale = null)
    {
        return $this->translator->translate($message, $textDomain, $locale);
    }
}
