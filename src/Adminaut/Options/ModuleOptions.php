<?php

namespace Adminaut\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 * @package Adminaut\Options
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $moduleId;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $moduleIcon;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $labels = [
        'add_entity' => 'Add entity',
        'update_entity' => 'Update entity',
        'entity_detail' => 'Entity detail'
    ];

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * @param int $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getModuleIcon()
    {
        return $this->moduleIcon;
    }

    /**
     * @param string $moduleIcon
     */
    public function setModuleIcon($moduleIcon)
    {
        $this->moduleIcon = $moduleIcon;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param array $labels
     */
    public function setLabels($labels)
    {
        $this->labels = array_merge($this->labels, $labels);
    }
}