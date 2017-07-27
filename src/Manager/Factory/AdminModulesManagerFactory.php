<?php
/**
 * Created by PhpStorm.
 * User: Josef
 * Date: 11.8.2016
 * Time: 14:07
 */

namespace Adminaut\Manager\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminModulesManagerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $modules = $serviceLocator->get('Config')['adminaut']['modules'];
        return new \Adminaut\Manager\AdminModulesManager($modules);
    }
}