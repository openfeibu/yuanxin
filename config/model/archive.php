<?php

return [

/*
 * Modules .
 */
    'modules'  => ['archive'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'archive'     => [
        'model'        => 'App\Models\Archive',
        'table'        => 'archives',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name', 'idcard', 'phone','email'],
        'translate'    => ['name', 'idcard', 'phone','email'],
        'upload_folder' => '/archive',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];
