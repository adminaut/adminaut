<?php

namespace Adminaut\Datatype\File\Factory;

use Adminaut\Datatype\File\DetailViewHelper;
use Adminaut\Manager\FileManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DetailViewHelperFactory
 * @package Adminaut\Datatype\File\Factory
 */
class DetailViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DetailViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FileManager $fileManager */
        $fileManager = $container->get(FileManager::class);

        return new DetailViewHelper($fileManager);
    }
}
