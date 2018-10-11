<?php
namespace Adminaut\Service\Factory;

use Adminaut\Service\MailService;
use Interop\Container\ContainerInterface;
use MassimoFilippi\MailModule\Adapter\Google\GoogleSmtpAdapter;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;
use MassimoFilippi\MailModule\Adapter\Mailjet\MailjetAdapter;
use MassimoFilippi\MailModule\Adapter\SparkPost\SparkPostAdapter;
use MassimoFilippi\MailModule\Adapter\SparkPost\SparkPostSmtpAdapter;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Renderer\PhpRenderer;

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
     * @return SlackService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');

        if (false === isset($config['adminaut']['mail_service'])) {
            throw new ServiceNotCreatedException('Missing configuration for mail module in adminaut.');
        }

        /** @var array $slackModuleConfig */
        $mailServiceConfig = $config['adminaut']['mail_service'];

        if (!isset($mailServiceConfig['enabled']) || false === $mailServiceConfig['enabled']) {
            throw new ServiceNotCreatedException('MailService is disabled, check your config.');
        }

        if (false === isset($mailServiceConfig['adapter'])) {
            throw new ServiceNotCreatedException('Missing adapter name.');
        }

        $adapterName = $mailServiceConfig['adapter'];

        switch ($adapterName) {
            case MailjetAdapter::class:
                if (false === isset($mailServiceConfig['adapter_params']['api_key'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "api_key".');
                }

                if (false === isset($mailServiceConfig['adapter_params']['api_secret'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "api_secret".');
                }

                $options = [];

                $options['api_key'] = $mailServiceConfig['adapter_params']['api_key'];
                $options['api_secret'] = $mailServiceConfig['adapter_params']['api_secret'];

                if (true === isset($mailServiceConfig['adapter_params']['sandbox_mode'])) {
                    $options['sandbox_mode'] = $mailServiceConfig['adapter_params']['sandbox_mode'];
                }

                $adapter = new MailjetAdapter($options);
                break;
            case SparkPostAdapter::class:
                if (false === isset($mailServiceConfig['adapter_params']['api_key'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "api_key".');
                }

                $options = [];

                $options['api_key'] = $mailServiceConfig['adapter_params']['api_key'];

                $adapter = new SparkPostAdapter($options);
                break;
            case SparkPostSmtpAdapter::class:
                if (false === isset($mailServiceConfig['adapter_params']['api_key'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "api_key".');
                }

                $options = [];

                $options['api_key'] = $mailServiceConfig['adapter_params']['api_key'];

                $adapter = new SparkPostSmtpAdapter($options);
                break;
            case GoogleSmtpAdapter::class:
                if (false === isset($mailServiceConfig['adapter_params']['username'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "username".');
                }

                if (false === isset($mailServiceConfig['adapter_params']['password'])) {
                    throw new ServiceNotCreatedException('Missing adapter parameter: "password".');
                }

                $options = [];

                $options['username'] = $mailServiceConfig['adapter_params']['username'];
                $options['password'] = $mailServiceConfig['adapter_params']['password'];
                $options['ssl'] = $mailServiceConfig['adapter_params']['ssl'] ?: 'tls';

                $adapter = new GoogleSmtpAdapter($options);
                break;
            default:
                throw new ServiceNotCreatedException(sprintf('Adapter "%s" could not be found.', $adapterName));
        }

        /** @var TranslatorInterface $translator */
        $translator = $container->get('translator');

        /** @var RendererInterface $viewRenderer */
        $viewRenderer = $container->get(PhpRenderer::class);

        return new MailService($adapter, $mailServiceConfig, $translator, $viewRenderer);
    }
}