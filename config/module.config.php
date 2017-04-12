<?php
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Adminaut\Controller\AclController::class         => \Adminaut\Controller\Factory\AclControllerFactory::class,
            Adminaut\Controller\InstallController::class     => \Adminaut\Controller\Factory\InstallControllerFactory::class,
            Adminaut\Controller\ModuleController::class      => \Adminaut\Controller\Factory\ModuleControllerFactory::class,
            Adminaut\Controller\UsersController::class       => \Adminaut\Controller\Factory\UsersControllerFactory::class,
            Adminaut\Controller\UserController::class        => \Adminaut\Controller\Factory\UserControllerFactory::class,
        ],

        'abstract_factories' => [
            \Adminaut\Controller\Factory\AdminautControllerAbstractFactory::class
        ]
    ],



    'controller_plugins' => [
        'factories' => [
            'userAuthentication'                              => \Adminaut\Controller\Plugin\Factory\UserAuthenticationControllerPluginFactory::class,
            'acl'                                             => \Adminaut\Controller\Plugin\Factory\AclControllerPluginFactory::class,
        ]
    ],



    'service_manager' => [
        'alias' => [
//            'UserAuthService' => \Zend\Authentication\AuthenticationService::class
        ],
        'factories' => [
            // Authentication
            Adminaut\Authentication\Adapter\Db::class               => Adminaut\Authentication\Adapter\Factory\DbFactory::class,
            Adminaut\Authentication\Storage\Db::class               => Adminaut\Authentication\Storage\Factory\DbFactory::class,
            'UserAuthService'                                       => Adminaut\Authentication\Factory\AuthenticationServiceFactory::class,
            \Adminaut\Authentication\Adapter\AdapterChain::class    => \Adminaut\Authentication\Adapter\Factory\AdapterChainFactory::class,

            // Controller
            \Adminaut\Controller\RedirectCallback::class            => \Adminaut\Controller\Factory\RedirectCallbackFactory::class,

            // Manager
            \Adminaut\Manager\ModuleManager::class                  => \Adminaut\Manager\Factory\ModuleManagerFactory::class,
            'AdminModulesManager'                                   => \Adminaut\Manager\Factory\AdminModulesManagerFactory::class,
            \Adminaut\Manager\FileManager::class                    => \Adminaut\Manager\Factory\FileManagerFactory::class,

            // Mapper
            \Adminaut\Mapper\UserMapper::class                      => \Adminaut\Mapper\Factory\UserMapperFactory::class,
            \Adminaut\Mapper\RoleMapper::class                      => \Adminaut\Mapper\Factory\RoleMapperFactory::class,
            'ResourceMapper'                                        => 'MfccAdminModule\Factory\Mapper\ResourceMapperFactory',

            //Navigation
            \Adminaut\Navigation\Navigation::class                  => \Adminaut\Navigation\NavigationFactory::class,

            // Options
            \Adminaut\Options\UserOptions::class                    => \Adminaut\Options\Factory\UserOptionsFactory::class,
            \Adminaut\Options\FileManagerOptions::class             => \Adminaut\Options\Factory\FileManagerOptionsFactory::class,

            // Service
            \Adminaut\Service\AccessControlService::class           => \Adminaut\Service\Factory\AccessControlServiceFactory::class,
            \Adminaut\Service\UserService::class                    => \Adminaut\Service\Factory\UserServiceFactory::class,

            \Zend\Mvc\I18n\Translator::class => \Zend\Mvc\Service\TranslatorServiceFactory::class,
        ]
    ],



    'view_helpers' => [
        'invokables' => [
            'formDate'                                              => \Adminaut\Form\View\Helper\FormDate::class,
            'formDateTime'                                          => \Adminaut\Form\View\Helper\FormDateTime::class,
            'formFile'                                              => \Adminaut\Form\View\Helper\FormFile::class,
//            'formCheckbox'                                          => \Adminaut\Form\View\Helper\FormCheckbox::class,
//            'formCheckbox'                                          => \Adminaut\Form\View\Helper\Checkbox::class,
        ],

        'factories' => [
            'formElement'                                           => \Adminaut\Form\View\Helper\Factory\FormElementFactory::class,
            'userIdentity'                                          => \Adminaut\View\Helper\Factory\UserIdentityViewHelperFactory::class,
            'isAllowed'                                             => \Adminaut\View\Helper\Factory\IsAllowedViewHelperFactory::class,
            'config'                                                => \Adminaut\View\Helper\Factory\ConfigViewHelperFactory::class,
        ]
    ],



    'form_elements' => [
        'invokables' => [
//            'Image'                                              => 'MfccAdminModule\Form\Element\Image'
        ],
        'initializers' => array(
            'ObjectManager' => \Adminaut\Initializer\ObjectManagerInitializer::class
        ),
    ],



    'doctrine' => [
        'driver' => [
            'adminaut_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Adminaut/Entity']
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
                        'controller' => \Adminaut\Controller\IndexController::class,
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
                                'controller' => \Adminaut\Controller\InstallController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'dashboard' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'dashboard',
                            'defaults' => [
                                'controller' => \Adminaut\Controller\DashboardController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'module' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'module',
                            'defaults' => [
                                'controller' => \Adminaut\Controller\ModuleController::class,
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
                                        'action' => 'index'
                                    ],
                                    'constraints' => [
                                        'mode'    => '(view|add|edit)',
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
                                                'tab' => 'main'
                                            ],
                                            'constraints' => [
                                                'cyclic_entity_id' => '[0-9]*',
                                                'tab' => '[a-z]*'
                                            ]
                                        ],
                                        'may_terminate' => true,
                                        'child_routes' => [
                                            'action' => [
                                                'type' => Segment::class,
                                                'options' => [
                                                    'route' => '/:cyclic_entity_id/:entity_action',
                                                    'defaults' => [
                                                        'action' => 'tab'
                                                    ],
                                                    'constraints' => [
                                                        'entity_action' => '[a-z]*',
                                                    ]
                                                ],
                                            ],
                                        ]
                                    ],
                                ]
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
                            ]
                        ],
                    ],
                    'users' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'users',
                            'defaults' => [
                                'controller' => \Adminaut\Controller\UsersController::class,
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
                                        'tab' => 'main'
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
                                                'tab' => 'main'
                                            ],
                                            'constraints' => [
                                                'tab' => '[a-z]*'
                                            ]
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
                                'controller' => \Adminaut\Controller\AclController::class,
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
                            ]
                        ],
                    ],
                    'settings' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'acl',
                            'defaults' => [
                                'controller' => \Adminaut\Controller\AclController::class,
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
                                'controller' => \Adminaut\Controller\UserController::class,
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
                                        'controller' => \Adminaut\Controller\UserController::class,
                                        'action' => 'login',
                                    ],
                                ],
                            ],
                            'forgot-password' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/forgot-password',
                                    'defaults' => [
                                        'controller' => \Adminaut\Controller\UserController::class,
                                        'action' => 'forgotPassword',
                                    ],
                                ],
                            ],
                            'authenticate' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/authenticate',
                                    'defaults' => [
                                        'controller' => \Adminaut\Controller\UserController::class,
                                        'action' => 'authenticate',
                                    ],
                                ],
                            ],
                            'logout' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/logout',
                                    'defaults' => [
                                        'controller' => \Adminaut\Controller\UserController::class,
                                        'action'     => 'logout',
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