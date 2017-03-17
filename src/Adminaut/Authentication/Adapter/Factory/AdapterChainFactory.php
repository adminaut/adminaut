<?php
namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AdapterChain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Adminaut\Options\UserOptions;
use Adminaut\Authentication\Adapter\Exception\OptionsNotFoundException;

/**
 * Class AdapterChainFactory
 * @package Adminaut\Authentication\Adapter
 */
class AdapterChainFactory implements FactoryInterface
{
    /**
     * @var UserOptions
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AdapterChain
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $chain = new AdapterChain();
        $options = $this->getOptions($serviceLocator);
        foreach ($options->getAuthAdapters() as $priority => $adapterName) {
            $adapter = $serviceLocator->get($adapterName);
            if (is_callable(array($adapter, 'authenticate'))) {
                $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'), $priority);
            }
            if (is_callable(array($adapter, 'logout'))) {
                $chain->getEventManager()->attach('logout', array($adapter, 'logout'), $priority);
            }
        }
        return $chain;
    }


    /**
     * @param UserOptions $options
     * @return $this
     */
    public function setOptions(UserOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param ServiceLocatorInterface|null $serviceLocator
     * @return UserOptions
     */
    public function getOptions(ServiceLocatorInterface $serviceLocator = null)
    {
        if (!$this->options) {
            if (!$serviceLocator) {
                throw new OptionsNotFoundException(
                    'Options were tried to retrieve but not set and no service locator was provided'
                );
            }
            $this->setOptions($serviceLocator->get(UserOptions::class));
        }
        return $this->options;
    }
}