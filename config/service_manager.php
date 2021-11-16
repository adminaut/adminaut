<?php

namespace Adminaut;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorServiceFactory;

return [
    'factories' => [
        // Authentication
        Authentication\Adapter\AuthAdapter::class => Authentication\Adapter\Factory\AuthAdapterFactory::class,
        Authentication\Storage\AuthStorage::class => Authentication\Storage\Factory\AuthStorageFactory::class,
        Authentication\Storage\CookieStorage::class => Authentication\Storage\Factory\CookieStorageFactory::class,
        Authentication\Service\AuthenticationService::class => Authentication\Service\Factory\AuthenticationServiceFactory::class,

        // Filesystem
        'adminautPrivateFilesystem' => Filesystem\Factory\PrivateFilesystemFactory::class,
        'adminautPrivateFilesystemAdapter' => Filesystem\Adapter\Factory\PrivateAdapterFactory::class,
        'adminautPublicFilesystem' => Filesystem\Factory\PublicFilesystemFactory::class,
        'adminautPublicFilesystemAdapter' => Filesystem\Adapter\Factory\PublicAdapterFactory::class,

        // Manager
        Manager\ModuleManager::class => Manager\Factory\ModuleManagerFactory::class,
        Manager\UserManager::class => Manager\Factory\UserManagerFactory::class,
        Manager\AdminautModulesManager::class => Manager\Factory\AdminautModulesManagerFactory::class,
        Manager\FileManager::class => Manager\Factory\FileManagerFactory::class,

        //Navigation
        Navigation\Navigation::class => Navigation\Factory\NavigationFactory::class,

        // Options
        Options\AdminautOptions::class => Options\Factory\AdminautOptionsFactory::class,
        Options\AppearanceOptions::class => Options\Factory\AppearanceOptionsFactory::class,
        Options\AuthAdapterOptions::class => Options\Factory\AuthAdapterOptionsFactory::class,
        Options\CookieStorageOptions::class => Options\Factory\CookieStorageOptionsFactory::class,
        Options\UsersOptions::class => Options\Factory\UsersOptionsFactory::class,

        // Service
        Service\AccessControlService::class => Service\Factory\AccessControlServiceFactory::class,
        Service\MailService::class => Service\Factory\MailServiceFactory::class,
        Service\ManifestService::class => Service\Factory\ManifestServiceFactory::class,
        'adminautSlackService' => Service\Factory\SlackServiceFactory::class,
        Service\ExportService::class => Service\Factory\ExportServiceFactory::class,

        // Translator service
        Translator::class => TranslatorServiceFactory::class,
    ],
    'aliases' => [
        'translator' => Translator::class,
    ],
];
