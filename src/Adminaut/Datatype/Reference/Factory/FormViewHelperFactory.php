<?php
namespace Adminaut\Datatype\Reference\Factory;

use Adminaut\Datatype\Reference\FormViewHelper;
use Adminaut\Manager\AdminModulesManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormViewHelperFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FormViewHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AdminModulesManager $adminModulesManager */
        $adminModulesManager = $serviceLocator->getServiceLocator()->get(\Adminaut\Manager\AdminModulesManager::class);
        return new FormViewHelper(
            $adminModulesManager
        );
    }
}