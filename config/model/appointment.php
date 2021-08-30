<?php

return [

/*
 * Modules .
 */
    'modules'  => ['appointment','appointment_date'],


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
        'fillable'     => ['user_id', 'project_id','number','name', 'idcard', 'phone','date','start_time','end_time','note','status','created_at','updated_at'],
        'translate'    => [],
        'upload_folder' => '/appointment',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'project_id' => '=',
            'name' => 'like',
            'number' => 'like',
            'status' => '=',
            'idcard' => 'like',
            'phone' => 'like',
        ],
        'status' => [
            'unchecked',
            'check'
        ],
    ],
    'appointment_date'     => [
        'model'        => 'App\Models\AppointmentDate',
        'table'        => 'appointment_dates',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['start_time', 'end_time','count'],
        'translate'    => [],
        'upload_folder' => '/appointment',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [

        ],
    ],
];
