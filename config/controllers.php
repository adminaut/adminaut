<?php

namespace Adminaut;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\Api\BaseApiController::class => InvokableFactory::class,
        Controller\Api\ModuleApiController::class => Controller\Api\Factory\ModuleApiControllerFactory::class,

        Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        Controller\DashboardController::class => InvokableFactory::class,
        Controller\IndexController::class => InvokableFactory::class,
        Controller\InstallController::class => Controller\Factory\InstallControllerFactory::class,
        Controller\ManifestController::class => Controller\Factory\ManifestControllerFactory::class,
        Controller\ModuleController::class => Controller\Factory\ModuleControllerFactory::class,
        //Controller\ModuleCyclicTabController::class => Controller\Factory\ModuleCyclicTabControllerFactory::class,
        //Controller\ModuleTabController::class => Controller\Factory\ModuleTabControllerFactory::class,
        Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
        Controller\UsersController::class => Controller\Factory\UsersControllerFactory::class,
    ],
];
