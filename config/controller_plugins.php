<?php

namespace Adminaut;

return [
    'factories' => [
        Controller\Plugin\AuthenticationPlugin::class => Controller\Plugin\Factory\AuthenticationPluginFactory::class,
        Controller\Plugin\IsAllowedPlugin::class => Controller\Plugin\Factory\IsAllowedPluginFactory::class,
        Controller\Plugin\ConfigPlugin::class => Controller\Plugin\Factory\ConfigPluginFactory::class,
        Controller\Plugin\TranslatePlugin::class => Controller\Plugin\Factory\TranslatePluginFactory::class,
        Controller\Plugin\TranslatePluralPlugin::class => Controller\Plugin\Factory\TranslatePluralPluginFactory::class,
        Controller\Plugin\ViewHelperPlugin::class => Controller\Plugin\Factory\ViewHelperPluginFactory::class,
    ],
    'aliases' => [
        'authentication' => Controller\Plugin\AuthenticationPlugin::class,
        'isAllowed' => Controller\Plugin\IsAllowedPlugin::class,
        'config' => Controller\Plugin\ConfigPlugin::class,
        'translate' => Controller\Plugin\TranslatePlugin::class,
        'translatePlural' => Controller\Plugin\TranslatePluralPlugin::class,
        'viewHelper' => Controller\Plugin\ViewHelperPlugin::class,
    ],
];
