<?php

namespace Adminaut;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
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
        $loadModules = $manager->getModules();
        $adminautModules = [
            'DoctrineModule',
            'DoctrineORMModule',
            'TwbBundle',
        ];
        $addModules = [];
        foreach ($adminautModules as $adminautModule) {
            if (!in_array($adminautModule, $loadModules)) {
                $addModules[] = $adminautModule;
            }
        }
        $manager->setModules(array_merge($addModules, $loadModules));
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
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
        return array(
            'initializers' => array(
                'ObjectManagerInitializer' => function ($element, $formElements) {
                    if ($element instanceof ObjectManagerAwareInterface) {
                        $services      = $formElements->getServiceLocator();
                        $entityManager = $services->get('Doctrine\ORM\EntityManager');

                        $element->setObjectManager($entityManager);
                    }
                },
            ),
        );
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
}