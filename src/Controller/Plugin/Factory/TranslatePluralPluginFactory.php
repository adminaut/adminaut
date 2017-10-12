<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\TranslatePluralPlugin;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TranslatePluralPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class TranslatePluralPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TranslatePluralPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        if (!$container->has('translator')) {
            throw new ServiceNotCreatedException('Zend I18n Translator not configured');
        }

        /** @var Translator $translator */
        $translator = $container->get('translator');

        return new TranslatePluralPlugin($translator);
    }
}
