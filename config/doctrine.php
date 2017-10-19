<?php

namespace Adminaut;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
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
];
