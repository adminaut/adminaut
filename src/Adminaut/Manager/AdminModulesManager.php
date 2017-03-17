<?php

namespace Adminaut\Manager;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class AdminModulesManager
 * @package Adminaut\Manager
 */
class AdminModulesManager implements ServiceManagerAwareInterface
{
    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * AdminModulesManager constructor.
     */
    public function __construct($modules)
    {
        array_unshift(
            $modules,
            [
                'label' => 'Dashboard',
                'route' => 'adminaut-dashboard',
                'icon' => 'fa fa-fw fa-dashboard',
            ]
        );

    }

    /**
     * @return array
     */
    public function getList()
    {
        //return $this->getMapper()->getList();
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}