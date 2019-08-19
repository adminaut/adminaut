<?php
namespace Adminaut\Service\Factory;

use Interop\Container\ContainerInterface;
use Maknz\Slack\Client;
use MassimoFilippi\SlackModule\Service\SlackService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SlackServiceFactory
 * @package Adminaut\Service\Factory
 */
class SlackServiceFactory implements FactoryInterface
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

        if (false === isset($config['adminaut']['slack'])) {
            throw new ServiceNotCreatedException('Missing configuration for slack in adminaut.');
        }

        /** @var array $slackModuleConfig */
        $slackModuleConfig = $config['adminaut']['slack'];

        if (!isset($config['adminaut']['slack']['enabled']) || false === $config['adminaut']['slack']['enabled']) {
            throw new ServiceNotCreatedException('Slack integration is disabled, check your config.');
        }

        /** @var Client $slackClient */
        $slackClient = new Client($slackModuleConfig['webhook_url'], [
            'channel' => $slackModuleConfig['defaults']['channel'] ?? null,
            'username' => $slackModuleConfig['defaults']['username'] ?? null,
            'icon' => $slackModuleConfig['defaults']['icon'] ?? null,
            'link_names' => $slackModuleConfig['link_names'] ?? false,
            'unfurl_links' => $slackModuleConfig['unfurl_links'] ?? false,
            'unfurl_media' => $slackModuleConfig['unfurl_media'] ?? true,
            'allow_markdown' => $slackModuleConfig['allow_markdown'] ?? true,
            'markdown_in_attachments' => $slackModuleConfig['markdown_in_attachments'] ?? [],
        ]);

        return new SlackService($slackClient);
    }
}