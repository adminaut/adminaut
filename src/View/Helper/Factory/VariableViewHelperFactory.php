<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\View\Helper\VariableViewHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VariableViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class VariableViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return VariableViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new VariableViewHelper($adminautOptions->getVariables());
    }
}
