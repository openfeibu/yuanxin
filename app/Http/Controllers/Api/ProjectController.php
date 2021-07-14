<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Banner;
use App\Models\Setting;
use Log;

class ProjectController extends BaseController
{
    public function __construct(ProjectRepository $projectRepository)
    {
        parent::__construct();
        $this->projectRepository = $projectRepository;
        $this->projectRepository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function getProjects(Request $request)
    {
        $projects = $this->projectRepository->orderBy('id','desc')->get();

        return $this->response->success()->data($projects)->json();

    }
    public function getProject(Request $request,$id)
    {
        $project = $this->projectRepository->find($id);
        return $this->response->success()->data($project->toArray())->json();
    }
}
