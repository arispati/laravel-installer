<?php

return [
    // token
    'token' => '',
    // id
    'id' => '',
    // commands
    'commands_update' => [],
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
