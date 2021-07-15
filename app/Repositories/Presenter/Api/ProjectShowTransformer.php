<?php

namespace App\Repositories\Presenter\Api;

use League\Fractal\TransformerAbstract;
use DB;

class ProjectShowTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Project $project)
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'attention' => replace_image_url($project->attention,config('app.image_url')),
            'image' => handle_image_url($project->image,config('app.image_url').'/image/original'),
        ];
    }
}
