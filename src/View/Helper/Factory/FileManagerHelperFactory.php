<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Manager\FileManager;
use Adminaut\View\Helper\FileManagerHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FileManagerHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class FileManagerHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FileManagerHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var FileManager $fileManager */
        $fileManager = $container->get(FileManager::class);

        return new FileManagerHelper($fileManager);
    }
}
