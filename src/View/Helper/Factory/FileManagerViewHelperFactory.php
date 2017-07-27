<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\View\Helper\FileManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FileManagerViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class FileManagerViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FileManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // todo: make as constructor DI
        $option = $container->get('FileManagerOptions');
        $viewHelper = new FileManager();
        $viewHelper->setService($container->get('FileManager'));
        $viewHelper->setParams($option->toArray());
        return $viewHelper;
    }
}
