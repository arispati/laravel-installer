<?php

return [
    // commands update
    'commands_update' => [
        [
            'command' => 'migrate',
            'args' => []
        ]
    ],
    // commands install
    'commands_install' => [
        [
            'command' => 'migrate',
            'args' => []
        ],
        [
            'command' => 'db:seed',
            'args' => [
                '--class' => 'AdminSeeder',
            ]
        ]
    ]
];
