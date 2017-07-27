<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Service\AccessControlService;
use Adminaut\View\Helper\IsAllowed;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IsAllowedViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class IsAllowedViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IsAllowed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        return new IsAllowed($accessControlService);
    }
}
