<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\ProjectRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.project.project.search');
    }
    public function model()
    {
        return config('model.project.project.model');
    }
    public function getProjects()
    {
        return $this->orderBy('id','asc')
            ->orderBy('order','asc')
            ->get();
    }
}