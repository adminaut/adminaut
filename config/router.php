<?php

namespace Adminaut;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;

const CONSTRAINT_KEY = '[a-zA-Z-]+';
const CONSTRAINT_ID = '[1-9][0-9]*';

return [
    'routes' => [
        'adminaut' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/admin[/]',
                'defaults' => [
                    'controller' => Controller\IndexController::class,
                    'action' => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes' => [
                'manifest' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'manifest',
                        'defaults' => [
                            'controller' => Controller\ManifestController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'install' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'install',
                        'defaults' => [
                            'controller' => Controller\InstallController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'dashboard' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'dashboard',
                        'defaults' => [
                            'controller' => Controller\DashboardController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'module' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'module',
                        'defaults' => [
                            'controller' => Controller\ModuleController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'list' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id',
                                'defaults' => [
                                    'action' => 'list',
                                ],
                                'constraints' => [
                                    'module_id' => CONSTRAINT_KEY,
                                ],
                            ],
                        ],
                        'action' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id/:mode[/:entity_id]',
                                'defaults' => [
                                    'tab' => 'main',
                                    'mode' => 'list',
                                    'action' => 'index',
                                ],
                                'constraints' => [
                                    'mode' => '(view|add|edit)',
                                    'module_id' => CONSTRAINT_KEY,
                                    'entity_id' => CONSTRAINT_ID,
                                ],
                            ],
                            'may_terminate' => true,
                            'child_routes' => [
                                'tab' => [
                                    'type' => Segment::class,
                                    'options' => [
                                        'route' => '/:tab',
                                        'defaults' => [
                                            'action' => 'tab',
                                            'tab' => 'main',
                                        ],
                                        'constraints' => [
                                            'cyclic_entity_id' => CONSTRAINT_ID,
                                            'tab' => CONSTRAINT_KEY,
                                        ],
                                    ],
                                    'may_terminate' => true,
                                    'child_routes' => [
                                        'action' => [
                                            'type' => Segment::class,
                                            'options' => [
                                                'route' => '/:cyclic_entity_id/:entity_action',
                                                'defaults' => [
                                                    'action' => 'tab',
                                                ],
                                                'constraints' => [
                                                    'entity_action' => CONSTRAINT_KEY,
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'delete' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id/delete/:entity_id',
                                'defaults' => [
                                    'action' => 'delete',
                                ],
                                'constraints' => [
                                    'module_id' => CONSTRAINT_KEY,
                                    'entity_id' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                    ],
                ],
                'users' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'users',
                        'defaults' => [
                            'controller' => Controller\UsersController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'view' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/view/:id',
                                'defaults' => [
                                    'action' => 'view',
                                ],
                                'constraints' => [
                                    'id' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'add' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/add',
                                'defaults' => [
                                    'action' => 'add',
                                ],
                            ],
                        ],
                        'edit' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/edit/:id',
                                'defaults' => [
                                    'action' => 'edit',
                                    'tab' => 'main',
                                ],
                                'constraints' => [
                                    'id' => CONSTRAINT_ID,
                                ],
                            ],
                            'may_terminate' => true,
                            'child_routes' => [
                                'tab' => [
                                    'type' => Segment::class,
                                    'options' => [
                                        'route' => '/:tab',
                                        'defaults' => [
                                            'action' => 'tab',
                                            'tab' => 'main',
                                        ],
                                        'constraints' => [
                                            'tab' => CONSTRAINT_KEY,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'delete' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/delete/:id',
                                'defaults' => [
                                    'action' => 'delete',
                                ],
                                'constraints' => [
                                    'id' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                    ],
                ],
                'acl' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'acl',
                        'defaults' => [
                            'controller' => Controller\AclController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'add-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/add-role',
                                'defaults' => [
                                    'action' => 'add-role',
                                ],
                            ],
                        ],
                        'view-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/view-role/:roleId',
                                'defaults' => [
                                    'action' => 'view-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'edit-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/edit-role/:roleId',
                                'defaults' => [
                                    'action' => 'edit-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'delete-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/delete-role/:roleId',
                                'defaults' => [
                                    'action' => 'delete-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                    ],
                ],
                'settings' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => 'acl',
                        'defaults' => [
                            'controller' => Controller\AclController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'add-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/add-role',
                                'defaults' => [
                                    'action' => 'add-role',
                                ],
                            ],
                        ],
                        'view-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/view-role/:roleId',
                                'defaults' => [
                                    'action' => 'view-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'edit-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/edit-role/:roleId',
                                'defaults' => [
                                    'action' => 'edit-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'delete-role' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/delete-role/:roleId',
                                'defaults' => [
                                    'action' => 'delete-role',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                        'edit-role-permission' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/edit-role-permission/:roleId',
                                'defaults' => [
                                    'action' => 'edit-role-permission',
                                ],
                                'constraints' => [
                                    'roleId' => CONSTRAINT_ID,
                                ],
                            ],
                        ],
                    ],
                ],
                'auth' => [
                    'type' => Literal::class,
                    'priority' => 1000,
                    'options' => [
                        'route' => 'auth',
                        'defaults' => [
                            'controller' => Controller\AuthController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'login' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/login',
                                'defaults' => [
                                    'action' => 'login',
                                ],
                            ],
                        ],
                        'logout' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/logout',
                                'defaults' => [
                                    'action' => 'logout',
                                ],
                            ],
                        ],
                        'forgotten-password' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/forgotten-password',
                                'defaults' => [
                                    'action' => 'forgottenPassword',
                                ],
                            ],
                        ],
                        'request-access' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/request-access',
                                'defaults' => [
                                    'action' => 'requestAccess',
                                ],
                            ],
                        ],
                    ],
                ],
                'profile' => [
                    'type' => Literal::class,
                    'priority' => 1000,
                    'options' => [
                        'route' => 'profile',
                        'defaults' => [
                            'controller' => Controller\ProfileController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'settings' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/settings',
                                'defaults' => [
                                    'action' => 'settings',
                                ],
                            ],
                        ],
                        'change-password' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/change-password',
                                'defaults' => [
                                    'action' => 'changePassword',
                                ],
                            ],
                        ],
                        'logins' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/logins',
                                'defaults' => [
                                    'action' => 'logins',
                                ],
                            ],
                        ],
                        'access-tokens' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/access-tokens',
                                'defaults' => [
                                    'action' => 'accessTokens',
                                ],
                            ],
                            'may_terminate' => true,
                            'child_routes' => [
                                'delete' => [
                                    'type' => Segment::class,
                                    'options' => [
                                        'route' => '/delete/:id',
                                        'defaults' => [
                                            'action' => 'deleteAccessToken',
                                        ],
                                        'constraints' => [
                                            'id' => CONSTRAINT_ID,
                                        ],
                                    ],
                                ],
                                'delete-all' => [
                                    'type' => Literal::class,
                                    'options' => [
                                        'route' => '/delete-all',
                                        'defaults' => [
                                            'action' => 'deleteAllAccessTokens',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
