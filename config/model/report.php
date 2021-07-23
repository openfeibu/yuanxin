<?php

return [

/*
 * Modules .
 */
    'modules'  => ['report'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'report'     => [
        'model'        => 'App\Models\Report',
        'table'        => 'reports',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['appointment_id', 'project_id', 'user_id','status'],
        'translate'    => [],
        'upload_folder' => '/report',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [

        ],
        'status' => ['good','abnormal'],
    ],
    'report_file'     => [
        'model'        => 'App\Models\ReportFile',
        'table'        => 'report_files',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['report_id','name','suffix', 'content','url', 'file_type'],
        'translate'    => [],
        'upload_folder' => '/report_file',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [

        ],
    ],

];
