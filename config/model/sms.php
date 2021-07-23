<?php

return [

/*
 * Modules .
 */
    'modules'  => ['sms'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'sms'     => [
        'model'        => 'App\Models\Sms',
        'table'        => 'sms',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['code','name','body', 'smsable_id', 'smsable_type'],
        'translate'    => [],
        'upload_folder' => '/sms',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
        ],
    ],

];
