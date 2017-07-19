<?php

namespace Adminaut;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Service\TranslatorServiceFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\AclController::class => Controller\Factory\AclControllerFactory::class,
            Controller\InstallController::class => Controller\Factory\InstallControllerFactory::class,
            Controller\ModuleController::class => Controller\Factory\ModuleControllerFactory::class,
            Controller\UsersController::class => Controller\Factory\UsersControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
        ],

        'abstract_factories' => [
            Controller\Factory\AdminautControllerAbstractFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\UserAuthentication::class => Controller\Plugin\Factory\UserAuthenticationFactory::class,
            Controller\Plugin\Acl::class => Controller\Plugin\Factory\AclFactory::class,
        ],
        'aliases' => [
            'userAuthentication' => Controller\Plugin\UserAuthentication::class,
            'acl' => Controller\Plugin\Acl::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            // Authentication
            Authentication\Adapter\AuthAdapter::class => Authentication\Adapter\Factory\AuthAdapterFactory::class,
            Authentication\Storage\AuthStorage::class => Authentication\Storage\Factory\AuthStorageFactory::class,
            Authentication\Storage\CookieStorage::class => Authentication\Storage\Factory\CookieStorageFactory::class,
            Authentication\Service\AuthenticationService::class => Authentication\Service\Factory\AuthenticationServiceFactory::class,

            // Manager
            Manager\ModuleManager::class => Manager\Factory\ModuleManagerFactory::class,
            Manager\AdminModulesManager::class => Manager\Factory\AdminModulesManagerFactory::class,
            Manager\FileManager::class => Manager\Factory\FileManagerFactory::class,

            // Mapper
            Mapper\UserMapper::class => Mapper\Factory\UserMapperFactory::class,
            Mapper\UserFailedLoginMapper::class => Mapper\Factory\UserFailedLoginMapperFactory::class,
            Mapper\RoleMapper::class => Mapper\Factory\RoleMapperFactory::class,

            //Navigation
            Navigation\Navigation::class => Navigation\NavigationFactory::class,

            // Options
            Options\UserOptions::class => Options\Factory\UserOptionsFactory::class,
            Options\FileManagerOptions::class => Options\Factory\FileManagerOptionsFactory::class,

            // Service
            Service\AccessControlService::class => Service\Factory\AccessControlServiceFactory::class,
            Service\UserService::class => Service\Factory\UserServiceFactory::class,

            // Translator service
            Translator::class => TranslatorServiceFactory::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'formDate' => Form\View\Helper\FormDate::class,
            'formDateTime' => Form\View\Helper\FormDateTime::class,
            'formFile' => Form\View\Helper\FormFile::class,
            //'formCheckbox'                                          => Form\View\Helper\FormCheckbox::class,
            //'formCheckbox'                                          => Form\View\Helper\Checkbox::class,
        ],

        'factories' => [
            'formElement' => Form\View\Helper\Factory\FormElementFactory::class,
            View\Helper\UserIdentity::class => View\Helper\Factory\UserIdentityFactory::class,
            'isAllowed' => View\Helper\Factory\IsAllowedViewHelperFactory::class,
            'config' => View\Helper\Factory\ConfigViewHelperFactory::class,
        ],

        'aliases' => [
            'userIdentity' => View\Helper\UserIdentity::class,
        ],
    ],

    'form_elements' => [
        'invokables' => [
//            'Image'                                              => 'MfccAdminModule\Form\Element\Image'
        ],
        'initializers' => [
            'ObjectManager' => Initializer\ObjectManagerInitializer::class,
        ],
    ],

    'doctrine' => [
        'driver' => [
            'adminaut_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Adminaut/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    'Adminaut\Entity' => 'adminaut_driver',
                ],
            ],
        ],
    ],

    'router' => [
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
                                'action' => 'manifest',
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
                                        'module_id' => '[a-z\-]*',
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
                                        'module_id' => '[a-z\-]*',
                                        'entity_id' => '[0-9]*',
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
                                                'cyclic_entity_id' => '[0-9]*',
                                                'tab' => '[a-z]*',
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
                                                        'entity_action' => '[a-z]*',
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
                                        'module_id' => '[a-z\-]*',
                                        'entity_id' => '[0-9]*',
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
                                        'id' => '[0-9]*',
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
                                        'id' => '[0-9]*',
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
                                                'tab' => '[a-z]*',
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
                                        'id' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
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
                                        'roleId' => '[0-9]*',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => Literal::class,
                        'priority' => 1000,
                        'options' => [
                            'route' => 'user',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
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
//                                        'controller' => Controller\UserController::class,
                                        'controller' => Controller\LoginController::class,
                                        'action' => 'login',
                                    ],
                                ],
                            ],
                            'forgot-password' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/forgot-password',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'forgotPassword',
                                    ],
                                ],
                            ],
                            'authenticate' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/authenticate',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'authenticate',
                                    ],
                                ],
                            ],
                            'logout' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/logout',
                                    'defaults' => [
//                                        'controller' => Controller\UserController::class,
                                        'controller' => Controller\LoginController::class,
                                        'action' => 'logout',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'layout/admin' => __DIR__ . '/../view/layout/layout-admin.phtml',
            'layout/admin-blank' => __DIR__ . '/../view/layout/layout-admin-blank.phtml',
        ],
        'template_path_stack' => [
            'Adminaut' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
