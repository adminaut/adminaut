<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\TranslatorPlugin;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TranslatorPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class TranslatorPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TranslatorPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        return new TranslatorPlugin($translator);
    }
}
