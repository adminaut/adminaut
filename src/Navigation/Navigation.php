<?php

namespace Adminaut\Navigation;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

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
        $authService = $container->get(AuthenticationService::class);
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
            'route' => 'adminaut/dashboard',
            'icon' => 'fa fa-fw fa-dashboard',
        ];

        foreach ($modules as $key => $item) {
            if ($item['type'] == 'section') {
                $pages[] = [
                    'label' => $item['label'],
                    'uri' => '#',
                    'section' => true,
                ];
            }
            if ($item['type'] == 'module') {
                if ($accessControl->isAllowed($key, AccessControlService::READ)) {
                    if (isset($item['module_icon'])) {
                        $icon = 'fa fa-fw ' . $item['module_icon'];
                    } else {
                        $icon = 'fa fa-fw fa-list-alt';
                    }
                    $pages[] = [
                        'label' => $item['module_name'],
                        'route' => 'adminaut/module/list',
                        'params' => [
                            'module_id' => $key,
                        ],
                        'icon' => $icon,
                        'pages' => [
                            [
                                'route' => 'adminaut/module/action',
                                'visible' => false,
                                'params' => [
                                    'module_id' => $key,
                                ],
                            ],
                        ],
                    ];
                }
            }
        }

        if ($accessControl->isAllowed('users', AccessControlService::READ)) {
            $pages[] = [
                'label' => 'System',
                'uri' => '#',
                'section' => true,
            ];
            if ($accessControl->isAllowed('users', AccessControlService::READ)) {
                $pages[] = [
                    'label' => 'Users',
                    'route' => 'adminaut/users',
                    'icon' => 'fa fa-fw fa-users',
                    'pages' => [
                    ],
                ];
            }
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
