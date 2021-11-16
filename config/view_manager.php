<?php

namespace Adminaut;

return [
    'template_map' => [
        // layout
        'layout/admin' => __DIR__ . '/../view/layout/layout-admin.phtml',
        'layout/admin-blank' => __DIR__ . '/../view/layout/layout-admin-blank.phtml',

        // partial
        'adminaut/partial/changeModal' => __DIR__ . '/../view/partial/changeModal.phtml',
        'adminaut/partial/breadcrumbs' => __DIR__ . '/../view/partial/breadcrumbs.phtml',
        'adminaut/partial/deleteModal' => __DIR__ . '/../view/partial/deleteModal.phtml',
        'adminaut/partial/exportModal' => __DIR__ . '/../view/partial/exportModal.phtml',
        'adminaut/partial/messages' => __DIR__ . '/../view/partial/messages.phtml',
        'adminaut/partial/navigation' => __DIR__ . '/../view/partial/navigation.phtml',
        'adminaut/partial/tabs' => __DIR__ . '/../view/partial/tabs.phtml',
    ],
    'template_path_stack' => [
        'Adminaut' => __DIR__ . '/../view', // todo: Remove this line after all adminaut views are in template_map above.
    ],
    'strategies' => [
        'ViewJsonStrategy',
    ],
];
