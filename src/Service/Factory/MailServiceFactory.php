<?php

namespace Adminaut\Service\Factory;

use Adminaut\Service\MailService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class MailServiceFactory
 * @package Adminaut\Service\Factory
 */
class MailServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return MailService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = [];

        /** @var array $config */
        $config = $container->get('Config');

        if (isset($config['adminaut']['mail_service']) && is_array($config['adminaut']['mail_service'])) {
            $options = $config['adminaut']['mail_service'];
        }

        return new MailService($options);
    }
}
