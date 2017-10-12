<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\View\Helper\GetDataTableLanguage;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GetDataTableLanguageFactory
 * @package Adminaut\View\Helper\Factory
 */
class GetDataTableLanguageFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GetDataTableLanguage
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        return new GetDataTableLanguage($authenticationService);
    }
}
