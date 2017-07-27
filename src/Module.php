<?php

namespace Adminaut;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Adminaut
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, InitProviderInterface
{
    /**
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        $adminautModules = [
            'DoctrineModule',
            'DoctrineORMModule',
            'TwbBundle',
            'BsbFlysystem',
            'Adminaut\Datatype',
        ];

        $loadedModules = $manager->getLoadedModules(false);
        foreach ($adminautModules as $adminautModule) {
            if (!in_array($adminautModule, $loadedModules)) {
                $manager->loadModule($adminautModule);
            }
        }
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getFormElementConfig()
    {
        return [
            'initializers' => [
                'ObjectManagerInitializer' => function ($element, $formElements) {
                    if ($element instanceof ObjectManagerAwareInterface) {
                        $services = $formElements->getServiceLocator();
                        $entityManager = $services->get('Doctrine\ORM\EntityManager');

                        $element->setObjectManager($entityManager);
                    }
                },
            ],
        ];
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    function onDispatchError(MvcEvent $e)
    {
        $vm = $e->getViewModel();
        $vm->setTemplate('layout/admin-blank');
    }
}
