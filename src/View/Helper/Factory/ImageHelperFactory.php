<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\View\Helper\ImageHelper;
use Interop\Container\ContainerInterface;
use League\Flysystem\Filesystem;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ImageHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class ImageHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ImageHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Filesystem $authenticationService */
        $privateFilesystem = $container->get('adminautPrivateFilesystem');

        /** @var Filesystem $authenticationService */
        $publicFilesystem = $container->get('adminautPublicFilesystem');

        return new ImageHelper($privateFilesystem, $publicFilesystem);
    }
}
