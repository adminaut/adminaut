<?php
namespace Adminaut\Controller\Factory;


use Adminaut\Options\UserOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RedirectCallbackFactory
 * @package Adminaut\Controller\Factory
 */
class RedirectCallbackFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = $serviceLocator->get('Router');
        $application = $serviceLocator->get('Application');
        $options = $serviceLocator->get(UserOptions::class);
        return new \Adminaut\Controller\RedirectCallback($application, $router, $options);
    }
}