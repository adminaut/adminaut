<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\AuthController;
use Adminaut\Manager\UserManager;
use Adminaut\Service\MailService;
use Interop\Container\ContainerInterface;
use MassimoFilippi\SlackModule\Service\SlackService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthControllerFactory
 * @package Adminaut\Controller\Factory
 */
class AuthControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        /** @var UserManager $userManager */
        $userManager = $container->get(UserManager::class);

        /** @var array $config */
        $config = $container->get('config');

        /** @var SlackService|null $slackService */
        $slackService = null;

        if (isset($config['adminaut']['slack']) && isset($config['adminaut']['slack']['enabled']) && true === $config['adminaut']['slack']['enabled']) {
            $slackService = $container->get('adminautSlackService');
        }

        return new AuthController(
            $authenticationService,
            $userManager,
            $slackService,
            $container->get(MailService::class)
        );
    }
}
