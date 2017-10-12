<?php

namespace Adminaut\Controller\Plugin;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class TranslatePluralPlugin
 * @package Adminaut\Controller\Plugin
 */
class TranslatePluralPlugin extends AbstractPlugin
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * TranslatePluralPlugin constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $singular
     * @param $plural
     * @param $number
     * @param string $textDomain
     * @param null $locale
     * @return string
     */
    public function __invoke($singular, $plural, $number, $textDomain = 'default', $locale = null)
    {
        return $this->translator->translatePlural($singular, $plural, $number, $textDomain, $locale);
    }
}
