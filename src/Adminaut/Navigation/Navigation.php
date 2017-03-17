<?php
namespace Adminaut\Navigation;

use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Navigation
 * @package Adminaut\Navigation
 */
class Navigation extends DefaultNavigationFactory
{
    /**
     * @param ContainerInterface $container
     * @return array
     * @throws \Zend\Navigation\Exception\InvalidArgumentException
     */
    protected function getPages(ContainerInterface $container)
    {
        /* @var $authService \Zend\Authentication\AuthenticationService */
        $authService = $container->get('UserAuthService');
        $accessControl = $container->get(AccessControlService::class);
        $accessControl->setUser($authService->getIdentity());

        $config = $container->get('config');
        if (isset($config['adminaut']['modules'])) {
            $modules = $config['adminaut']['modules'];
        } else {
            $modules = [];
        }

        $pages[] = [
            'label' => 'Dashboard',
            'route' => 'adminaut-dashboard',
            'icon' => 'fa fa-fw fa-dashboard'
        ];

        foreach ($modules as $key => $item) {
            if ($item['type'] == 'module') {
                if ($accessControl->isAllowed($key, AccessControlService::READ)) {
                    if (isset($item['module_icon'])) {
                        $icon = 'fa fa-fw ' . $item['module_icon'];
                    } else {
                        $icon = 'fa fa-fw fa-list-alt';
                    }
                    $pages[] = [
                        'label' => $item['module_name'],
                        'route' => 'adminaut-module/list',
                        'params' => [
                            'module_id' => $key,
                        ],
                        'icon' => 'fa fa-fw ' . $icon,
                    ];
                }
            }
        }

        if ($accessControl->isAllowed('users', AccessControlService::READ)) {
            $subPage = [];
            if ($accessControl->isAllowed('users', AccessControlService::READ)) {
                $subPage[] = [
                    'label' => 'Users',
                    'route' => 'adminaut-users/list',
                    'icon' => 'fa fa-fw fa-user',
                ];
            }
            $pages[] = [
                'label' => 'User Management',
                'uri' => '#',
                'icon' => 'fa fa-fw fa-users',
                'pages' => $subPage,
            ];
        }

        if (null === $this->pages) {
            $mvcEvent = $container->get('Application')->getMvcEvent();
            $routeMatch = $mvcEvent->getRouteMatch();
            $router = $mvcEvent->getRouter();
            $pages = $this->getPagesFromConfig($pages);
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }
}