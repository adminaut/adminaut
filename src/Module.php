<?php

namespace Adminaut;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Adminaut
 */
class Module implements ConfigProviderInterface, InitProviderInterface, BootstrapListenerInterface
{

    /**
     * @param MvcEvent $e
     */
    function onDispatchError(MvcEvent $e)
    {
        $vm = $e->getViewModel();
        $vm->setTemplate('layout/admin-blank');
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface|MvcEvent $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        return [];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $adminautModules = [
            'DoctrineModule',
            'DoctrineORMModule',
            'TwbBundle',
            'BsbFlysystem',
        ];

        $loadedModules = $manager->getLoadedModules(false);
        foreach ($adminautModules as $adminautModule) {
            if (!in_array($adminautModule, $loadedModules)) {
                $manager->loadModule($adminautModule);
            }
        }
    }
}
