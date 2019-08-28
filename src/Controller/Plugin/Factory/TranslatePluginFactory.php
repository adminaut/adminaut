<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\TranslatePlugin;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TranslatePluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class TranslatePluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TranslatePlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if ( !( $container->has('MvcTranslator') || $container->has(TranslatorInterface::class) || $container->has('Translator')) ) {
            throw new ServiceNotCreatedException('Zend I18n Translator not configured');
        }

        /** @var TranslatorInterface $translator */
        if ($container->has('MvcTranslator')) {
            $translator = $container->get('MvcTranslator');
        } elseif ($container->has(TranslatorInterface::class)) {
            $translator = $container->get(TranslatorInterface::class);
        } elseif ($container->has('Translator')) {
            $translator = $container->get('Translator');
        }

        return new TranslatePlugin($translator);
    }
}
