<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\TranslatePlugin;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
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

        if (!$container->has('translator')) {
            throw new ServiceNotCreatedException('Zend I18n Translator not configured');
        }

        /** @var Translator $translator */
        $translator = $container->get('translator');

        return new TranslatePlugin($translator);
    }
}
