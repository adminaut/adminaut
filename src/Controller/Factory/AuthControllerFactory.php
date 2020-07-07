<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\AuthController;
use Adminaut\Manager\UserManager;
use Adminaut\Service\MailService;
use Interop\Container\ContainerInterface;
use MassimoFilippi\SlackModule\Service\SlackService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
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

        if ( !( $container->has('MvcTranslator') || $container->has(TranslatorInterface::class) || $container->has('Translator')) ) {
            throw new ServiceNotCreatedException('Zend I18n Translator not configured');
        }

        /** @var TranslatorInterface $translator */
        if ($container->has('MvcTranslator')) {
            $translator = $container->get('MvcTranslator');
        } elseif ($container->has(TranslatorInterface::class)) {
            $translator = $container->get(TranslatorInterface::class);
        } elseif ($container->has('Translator')) {
            $translator = $container->get('Translator');
        }

        return new AuthController(
            $authenticationService,
            $userManager,
            $slackService,
            $container->get(MailService::class),
            $translator
        );
    }
}
