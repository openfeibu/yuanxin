<?php

return [

    /*
     * Package.
     */
    'package'   => 'menu',

    /*
     * Modules.
     */
    'modules'   => ['menu'],

    'menu'       => [
        'model'             => 'App\Models\Menu',
        'table'             => 'menus',
        'hidden'            => [],
        'visible'           => [],
        'guarded'           => ['*'],
        'slugs'             => ['slug' => 'name'],
        'dates'             => ['deleted_at'],
        'appends'           => [],
        'fillable'          => ['user_id', 'parent_id',  'key',  'url',  'icon',  'permission',  'role',  'name',  'description',  'target',  'order',  'uload_folder'],
        'translate'         => ['parent_id',  'key',  'url',  'icon',  'permission',  'role',  'name',  'description',  'target',  'order',  'uload_folder'],
        'upload_folder'     => 'menu/menu',
        'uploads'           => [],
        'casts'         => [
            'role' => 'array',
        ],
        'revision'          => [],
        'perPage'           => '20',
        'search'        => [
            'name'  => 'like',
            'status',
        ],

    ],
];
