<?php

return [

/*
 * Modules .
 */
    'modules'  => ['appointment'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'appointment'     => [
        'model'        => 'App\Models\Appointment',
        'table'        => 'appointments',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['user_id', 'project_id','name', 'idcard', 'phone','date','start_time','end_time','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/appointment',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [

        ],
    ],

];
