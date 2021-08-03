<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\ReportFile;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ReportFileRepository;
use App\Repositories\Eloquent\ReportFileRepositoryInterface;
use App\Repositories\Eloquent\ReportRepository;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Resource controller class for page.
 */
class ReportFileResourceController extends BaseController
{
    /**
     * Initialize page resource controller.
     *
     * @param type ReportRepository $reportRepository
     * @param type ReportFileRepository $report_fileFileRepository
     * @param type AppointmentRepository $appointmentRepository
     */
    public function __construct(
        ReportRepository $reportRepository,
        ReportFileRepository $reportFileRepository,
        AppointmentRepository $appointmentRepository)
    {
        parent::__construct();
        $this->repository = $reportFileRepository;
        $this->reportRepository = $reportRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request){
        $limit = $request->input('limit',config('app.limit'));
        $report_id = $request->report_id;
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->setPresenter(\App\Repositories\Presenter\ReportFilePresenter::class)
                ->when($report_id,function($query) use ($report_id){
                    return $query->where('report_id',$report_id);
                })
                ->orderBy('id','desc')
                ->getDataTable($limit);
            return $this->response
                ->success()
                ->count($data['recordsTotal'])
                ->data($data['data'])
                ->output();
        }
        return $this->response->title(trans('report_file.name'))
            ->view('report_file.index')
            ->output();
    }
    public function create(Request $request)
    {
        $report_id = $request->report_id;
        $report = $this->reportRepository->where('id',$report_id)->first();
        if(!$report)
        {
            return $this->response->message("报告单不存在")
                ->code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }

        $report_file = $this->repository->newInstance([]);

        return $this->response->title(trans('report_file.name'))
            ->view('report_file.create')
            ->data(compact('report','report_file'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $report_id = $request->report_id;
            $report = $this->reportRepository->where('id',$report_id)->first();
            if(!$report)
            {
                return $this->response->message("报告单不存在")
                    ->code(400)
                    ->status('error')
                    ->url(url()->previous())
                    ->redirect();
            }
            $pathinfo = pathinfo($attributes['url']);
            $report_file = $this->repository->create([
                'report_id' => $report_id,
                'name' => $attributes['name'].'.'.$pathinfo['extension'],
                'suffix' => $pathinfo['extension'],
                'url' => $attributes['url'],
                'file_type' => isset(config('common.img_type')[$pathinfo['extension']]) ? 'image' : (isset(config('common.file_type')[$pathinfo['extension']]) ? 'file' : (isset(config('common.video_type')[$pathinfo['extension']]) ? 'video' : 'other'))
            ]);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('report_file.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('report/'.$report_id ))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
    }
    public function show(Request $request,ReportFile $report_file)
    {
        if ($report_file->exists) {
            $view = 'report_file.show';
        } else {
            $view = 'report_file.new';
        }

        return $this->response->title(trans('report_file.name'))
            ->data(compact('report'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,ReportFile $report_file)
    {
        try {
            $attributes = $request->all();

            $report_file->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('report_file.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('report/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('report/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,ReportFile $report_file)
    {
        try {
            $report_file->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('report_file.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('report'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('report'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('report_file.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('report'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('report'))
                ->redirect();
        }
    }

}