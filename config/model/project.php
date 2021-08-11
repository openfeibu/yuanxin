<?php

return [

/*
 * Modules .
 */
    'modules'  => ['project'],


/*
 * Views for the page  .
 */
    'views'    => ['default' => 'Default', 'left' => 'Left menu', 'right' => 'Right menu'],

// Modale variables for page module.
    'project'     => [
        'model'        => 'App\Models\Project',
        'table'        => 'projects',
        'primaryKey'   => 'id',
        'hidden'       => [],
        'visible'      => [],
        'guarded'      => ['*'],
        'fillable'     => ['name','image', 'content', 'description','status','order'],
        'translate'    => [],
        'upload_folder' => '/project',
        'encrypt'      => ['id'],
        'revision'     => ['name'],
        'perPage'      => '20',
        'search'        => [
            'name'  => 'like',
        ],
    ],

];
