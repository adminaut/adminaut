<?php
namespace Adminaut\Controller\Api\Factory;

use Adminaut\Controller\Api\ModuleApiController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\ExportService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModuleApiControllerFactory
 */
class ModuleApiControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleApiController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        $accessControlService = $container->get(AccessControlService::class);
        
        $viewHelperManager = $container->get('ViewHelperManager');

        return new ModuleApiController(
            $moduleManager,
            $accessControlService,
            $viewHelperManager,
            $container->get(ExportService::class)
        );
    }
}