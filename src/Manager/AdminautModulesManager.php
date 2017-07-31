<?php

namespace Adminaut\Manager;

use Adminaut\Options\ModuleOptions;

/**
 * Class AdminautModulesManager
 * @package Adminaut\Manager
 */
class AdminautModulesManager
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
     * AdminautModulesManager constructor.
     * @param array $modules
     */
    public function __construct(array $modules)
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

        foreach ($this->modules as $moduleId => $module) {
            if (isset($module['entity_class'])) {
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
     * @param string $moduleId
     * @return string|false
     */
    public function getModuleEntity($moduleId)
    {
        if (isset($this->entities[$moduleId])) {
            return $this->entities[$moduleId];
        }
        return false;
    }

    /**
     * @param $moduleId
     */
    public function getModuleOptionsByModuleId($moduleId)
    {
        return new ModuleOptions();
    }

    /**
     * @param string $entityClass
     * @return string|false
     */
    public function getModuleByEntityClass($entityClass)
    {
        return array_search($entityClass, $this->entities);
    }
}
