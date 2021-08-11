<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Project;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Eloquent\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Resource controller class for page.
 */
class ProjectResourceController extends BaseController
{
    /**
     * Initialize page resource controller.
     *
     * @param type ProjectRepository $projectRepository
     *
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        parent::__construct();
        $this->repository = $projectRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request){
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->setPresenter(\App\Repositories\Presenter\ProjectListPresenter::class)
                ->orderBy('order','asc')
                ->orderBy('id','asc')
                ->get();
            return $this->response
                ->success()
                ->data($data['data'])
                ->output();
        }
        return $this->response->title(trans('project.name'))
            ->view('project.index')
            ->output();
    }
    public function create(Request $request)
    {
        $project = $this->repository->newInstance([]);

        return $this->response->title(trans('project.name'))
            ->view('project.create')
            ->data(compact('project'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            if(isset($attributes['status']) && $attributes['status'])
            {
                $attributes['status'] = 'show';
            }else{
                $attributes['status'] = 'hide';
            }
            $project = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('project.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('project/' ))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('project/'))
                ->redirect();
        }
    }
    public function show(Request $request,Project $project)
    {
        if ($project->exists) {
            $view = 'project.show';
        } else {
            $view = 'project.new';
        }

        return $this->response->title(trans('project.name'))
            ->data(compact('project'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Project $project)
    {
        try {
            $attributes = $request->all();
            if(isset($attributes['status'])) {
                if ($attributes['status']) {
                    $attributes['status'] = 'show';
                } else {
                    $attributes['status'] = 'hide';
                }
            }
            $project->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('project.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('project/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('project/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,Project $project)
    {
        try {
            $project->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('project.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('project'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('project'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('project.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('project'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('project'))
                ->redirect();
        }
    }

}