<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;
use DB;

class ProjectListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Project $project)
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'content' => replace_image_url($project->content,config('app.image_url')),
            'image' => handle_image_url($project->image,config('app.image_url').'/image/original'),
            'order' => $project->order,
        ];
    }
}
