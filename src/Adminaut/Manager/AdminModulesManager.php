<?php

namespace Adminaut\Manager;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class AdminModulesManager
 * @package Adminaut\Manager
 */
class AdminModulesManager
{
    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var array
     */
    protected $entities = [];

    /**
     * AdminModulesManager constructor.
     */
    public function __construct($modules)
    {
       array_unshift(
            $modules,
            [
                'label' => 'Dashboard',
                'route' => 'adminaut/dashboard',
                'icon' => 'fa fa-fw fa-dashboard',
            ]
        );
       $this->modules = $modules;

        foreach($this->modules as $moduleId => $module) {
            if(isset($module['entity_class'])) {
                $this->entities[$moduleId] = $module['entity_class'];
            }
        }
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @param string $moduleId
     * @return string|false
     */
    public function getModuleEntity($moduleId) {
        if(isset($this->entities[$moduleId])) {
            return $this->entities[$moduleId];
        } else {
            return false;
        }
    }

    /**
     * @param string $entityClass
     * @return string|false
     */
    public function getModuleByEntityClass($entityClass) {
        return array_search($entityClass, $this->entities);
    }
}