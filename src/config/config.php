<?php

return [
    // token
    'token' => '',
    // id
    'id' => '',
    // commands
    'commands' => [
        // update commands
        'update' => [],
        // install commands
        'install' => [
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
    ]
];
