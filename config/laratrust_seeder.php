<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'posts' => 'c,r,u,d',
            'tags' => 'c,r,u,d',
            'comments' => 'c,r,u,d'
        ],

        'author' => [
            'posts' => 'c,r,u,d',
            'tags' => 'c,r,u,d',
            'comments' => 'c,r,u,d'
        ],

        'user' => [
            'posts' => 'r',
            'tags' => 'r',
            'comments' => 'c,r,u,d'
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
