<?php
namespace Adminaut\Service\Factory;

use Adminaut\Manager\ModuleManager;
use Adminaut\Service\AccessControlService;
use Adminaut\Service\ExportService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ExportServiceFactory
 */
class ExportServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ExportService|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ExportService(
            $container->get(ModuleManager::class),
            $container->get(AccessControlService::class),
            $container->get('translator')
        );
    }
}