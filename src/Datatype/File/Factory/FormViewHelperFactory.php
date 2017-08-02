<?php

namespace Adminaut\Datatype\File\Factory;

use Adminaut\Datatype\File\FormViewHelper;
use Adminaut\Manager\FileManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormViewHelperFactory
 * @package Adminaut\Datatype\File\Factory
 */
class FormViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FormViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FileManager $fileManager */
        $fileManager = $container->get(FileManager::class);

        return new FormViewHelper($fileManager);
    }
}
