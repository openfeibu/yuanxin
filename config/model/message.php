<?php

return [

    /*
     * Package.
     */
    'package'   => 'message',

    /*
     * Modules.
     */
    'modules'   => ['message'],

    'message'       => [
        'model'             => 'App\Models\Message',
        'table'             => 'messages',
        'hidden'            => [],
        'visible'           => [],
        'guarded'           => ['*'],
        'slugs'             => [],
        'dates'             => ['deleted_at'],
        'appends'           => [],
        'fillable'          => ['user_id', 'admin_user_id', 'content', 'type', 'is_reply'],
        'translate'         => ['user_id', 'admin_user_id', 'content', 'type', 'is_reply'],
        'upload_folder'     => '/message',
        'uploads'           => [],
        'casts'         => [

        ],
        'revision'          => [],
        'perPage'           => '20',
        'search'        => [

        ],

    ],
];
