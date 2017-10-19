<?php

namespace Adminaut;

return [
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
];
