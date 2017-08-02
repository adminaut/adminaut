<?php

namespace Adminaut;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorServiceFactory;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            //Controller\AclController::class => Controller\Factory\AclControllerFactory::class,
            Controller\InstallController::class => Controller\Factory\InstallControllerFactory::class,
            Controller\ModuleController::class => Controller\Factory\ModuleControllerFactory::class,
            Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
            Controller\UsersController::class => Controller\Factory\UsersControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ManifestController::class => InvokableFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\AuthenticationPlugin::class => Controller\Plugin\Factory\AuthenticationPluginFactory::class,
            Controller\Plugin\IsAllowedPlugin::class => Controller\Plugin\Factory\IsAllowedPluginFactory::class,
            Controller\Plugin\ConfigPlugin::class => Controller\Plugin\Factory\ConfigPluginFactory::class,
            Controller\Plugin\TranslatePlugin::class => Controller\Plugin\Factory\TranslatePluginFactory::class,
            Controller\Plugin\TranslatePluralPlugin::class => Controller\Plugin\Factory\TranslatePluralPluginFactory::class,
        ],
        'aliases' => [
            'authentication' => Controller\Plugin\AuthenticationPlugin::class,
            'isAllowed' => Controller\Plugin\IsAllowedPlugin::class,
            'config' => Controller\Plugin\ConfigPlugin::class,
            'translate' => Controller\Plugin\TranslatePlugin::class,
            'translatePlural' => Controller\Plugin\TranslatePluralPlugin::class,
        ],
    ],

    'service_manager' => [

        'factories' => [
            // Authentication
            Authentication\Adapter\AuthAdapter::class => Authentication\Adapter\Factory\AuthAdapterFactory::class,
            Authentication\Adapter\AuthAdapterOptions::class => Authentication\Adapter\Factory\AuthAdapterOptionsFactory::class,
            Authentication\Storage\AuthStorage::class => Authentication\Storage\Factory\AuthStorageFactory::class,
            Authentication\Storage\CookieStorage::class => Authentication\Storage\Factory\CookieStorageFactory::class,
            Authentication\Storage\CookieStorageOptions::class => Authentication\Storage\Factory\CookieStorageOptionsFactory::class,
            Authentication\Service\AuthenticationService::class => Authentication\Service\Factory\AuthenticationServiceFactory::class,

            // Manager
            Manager\ModuleManager::class => Manager\Factory\ModuleManagerFactory::class,
            Manager\UserManager::class => Manager\Factory\UserManagerFactory::class,
            Manager\AdminautModulesManager::class => Manager\Factory\AdminautModulesManagerFactory::class,
            Manager\FileManager::class => Manager\Factory\FileManagerFactory::class,

            //Navigation
            Navigation\Navigation::class => Navigation\Factory\NavigationFactory::class,

            // Options
            Options\AdminautOptions::class => Options\Factory\AdminautOptionsFactory::class,
            Options\FileManagerOptions::class => Options\Factory\FileManagerOptionsFactory::class,

            // Service
            Service\AccessControlService::class => Service\Factory\AccessControlServiceFactory::class,

            // Translator service
            Translator::class => TranslatorServiceFactory::class,
        ],

        'aliases' => [
            'translator' => Translator::class,
        ],
    ],

    'dependencies' => [

        'factories' => [
            'FormElementManager' => Datatype\DatatypeManagerFactory::class,
        ],
    ],

    'view_helpers' => [

        'invokables' => [
            // form collection
            'formCollection' => Datatype\View\Helper\FormCollection::class, // todo: rename to datatypeFormCollection so we don't overwrite default formCollection?

            // form helpers
            'datatypeFormSelect' => Datatype\Select\FormViewHelper::class,
            'datatypeFormCheckbox' => Datatype\Checkbox\FormViewHelper::class,
            'datatypeFormMultiCheckbox' => Datatype\MultiCheckbox\FormViewHelper::class,
            'datatypeFormMultiReference' => Datatype\MultiReference\FormViewHelper::class,
            'datatypeFormLocation' => Datatype\Location\FormViewHelper::class,
            'datatypeFormGoogleMap' => Datatype\GoogleMap\FormViewHelper::class,
            'datatypeFormGoogleStreetView' => Datatype\GoogleStreetView\FormViewHelper::class,
            'datatypeFormGooglePlaceId' => Datatype\GooglePlaceId\FormViewHelper::class,
            'datatypeFormDateTime' => Datatype\DateTime\FormViewHelper::class,
            'datatypeFormTextarea' => Datatype\Textarea\FormViewHelper::class,

            // detail helpers
            'datatypeDetail' => Datatype\View\Helper\datatypeDetailViewHelper::class,
            'datatypeLocationDetail' => Datatype\Location\DetailViewHelper::class,
            'datatypeGoogleMapDetail' => Datatype\GoogleMap\DetailViewHelper::class,
            'datatypeGoogleStreetViewDetail' => Datatype\GoogleStreetView\DetailViewHelper::class,
            'datatypeTextareaDetail' => Datatype\Textarea\DetailViewHelper::class,

            'formrow' => Datatype\View\Helper\FormRow::class,
            'form_row' => Datatype\View\Helper\FormRow::class,
            'formRow' => Datatype\View\Helper\FormRow::class,
            'FormRow' => Datatype\View\Helper\FormRow::class,
        ],

        'factories' => [
            Form\View\Helper\FormElement::class => Form\View\Helper\Factory\FormElementFactory::class,
            View\Helper\UserIdentity::class => View\Helper\Factory\UserIdentityFactory::class,
            View\Helper\IsAllowed::class => View\Helper\Factory\IsAllowedViewHelperFactory::class,
            View\Helper\ConfigViewHelper::class => View\Helper\Factory\ConfigViewHelperFactory::class,

            Datatype\File\FormViewHelper::class => Datatype\File\Factory\FormViewHelperFactory::class,
            'formElement' => Form\View\Helper\Factory\FormElementFactory::class,
            'FormElement' => Form\View\Helper\Factory\FormElementFactory::class,
            'form_element' => Form\View\Helper\Factory\FormElementFactory::class,
            'formelement' => Form\View\Helper\Factory\FormElementFactory::class,

            // Datatype helpers
            Datatype\View\Helper\FormRow::class => Datatype\View\Helper\Factory\FormRowFactory::class,
            Datatype\View\Helper\Datatype::class => Datatype\View\Helper\Factory\DatatypeFactory::class,

            'datatypeFormReference' => Datatype\Reference\Factory\FormViewHelperFactory::class,
        ],

        'aliases' => [
            'userIdentity' => View\Helper\UserIdentity::class,
            'isAllowed' => View\Helper\IsAllowed::class,
            'config' => View\Helper\ConfigViewHelper::class,


            'datatypeFormFile' => Datatype\File\FormViewHelper::class,
            'formElement' => Form\View\Helper\FormElement::class,
            'FormElement' => Form\View\Helper\FormElement::class,
            'form_element' => Form\View\Helper\FormElement::class,
            'formelement' => Form\View\Helper\FormElement::class,


            // Datatype helpers
            'formrow' => Datatype\View\Helper\FormRow::class,
            'form_row' => Datatype\View\Helper\FormRow::class,
            'formRow' => Datatype\View\Helper\FormRow::class,
            'FormRow' => Datatype\View\Helper\FormRow::class,
            'datatype' => Datatype\View\Helper\Datatype::class,
        ],
    ],

    'form_elements' => [
        'initializers' => [
//            'ObjectManager' => Initializer\ObjectManagerInitializer::class,
//            'ObjectManagerInitializer' => Initializer\ObjectManagerInitializer::class,
//            'ObjectManager' => function ($element, $formElements) {
//                if ($element instanceof ObjectManagerAwareInterface) {
//                    $services = $formElements->getServiceLocator();
//                    $entityManager = $services->get('Doctrine\ORM\EntityManager');
//
//                    $element->setObjectManager($entityManager);
//                }
//            },
        ],
        'factories' => [
            Initializer\ObjectManagerInitializer::class => Initializer\Factory\ObjectManagerInitializerFactory::class,
        ],

    ],

    'doctrine' => [
        'driver' => [
            'adminaut_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
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
