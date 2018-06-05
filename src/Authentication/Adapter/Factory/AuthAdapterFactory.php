<?php

namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Options\AuthAdapterOptions;
use Adminaut\Service\MailService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthAdapterFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
class AuthAdapterFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var AuthAdapterOptions $options */
        $options = $container->get(AuthAdapterOptions::class);

        /** @var array $config */
        $config = $container->get('config');

        /** @var MailService|null $mailService */
        $mailService = null;

        if (isset($config['adminaut']['mail_service']) && isset($config['adminaut']['mail_service']['enabled']) && true === $config['adminaut']['mail_service']['enabled']) {
            $mailService = $container->get(MailService::class);
        }

        return new AuthAdapter($entityManager, $options, $mailService);
    }
}
