<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Report;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ReportRepository;
use App\Repositories\Eloquent\ReportRepositoryInterface;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Resource controller class for page.
 */
class ReportResourceController extends BaseController
{
    /**
     * Initialize page resource controller.
     *
     * @param type ReportRepository $reportRepository
     *
     */
    public function __construct(ReportRepository $reportRepository,
    AppointmentRepository $appointmentRepository)
    {
        parent::__construct();
        $this->repository = $reportRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request){
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->setPresenter(\App\Repositories\Presenter\ReportPresenter::class)
                ->join('appointments','appointments.id','=','reports.appointment_id')
                ->when(isset($search['name']) && $search['name'],function($query) use ($search){
                    return $query->where('appointments.name','like','%'.$search['name'].'%');
                })
                ->when(isset($search['phone']) && $search['phone'],function($query) use ($search){
                    return $query->where('appointments.phone','like','%'.$search['phone'].'%');
                })
                ->when(isset($search['project_id']) && $search['project_id'],function($query) use ($search){
                    return $query->where('reports.project_id',$search['project_id']);
                })
                ->orderBy('id','desc')
                ->getDataTable($limit,['reports.*']);
            return $this->response
                ->success()
                ->count($data['recordsTotal'])
                ->data($data['data'])
                ->output();
        }
        return $this->response->title(trans('report.name'))
            ->view('report.index')
            ->output();
    }
    public function create(Request $request)
    {
        $appointment_id = $request->appointment_id;
        $appointment = $this->appointmentRepository->where('id',$appointment_id)->first();
        if(!$appointment)
        {
            return $this->response->message("预约不存在")
                ->code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
        $report = $this->repository->where('appointment_id',$appointment_id)->first();
        if($report)
        {
            return redirect(guard_url('report/'.$report->id));
        }
        $report = $this->repository->newInstance([]);

        return $this->response->title(trans('report.name'))
            ->view('report.create')
            ->data(compact('report','appointment'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $appointment_id = $request->appointment_id;
            $appointment = $this->appointmentRepository->where('id',$appointment_id)->first();
            if(!$appointment)
            {
                return $this->response->message("预约不存在")
                    ->code(400)
                    ->status('error')
                    ->url(url()->previous())
                    ->redirect();
            }
            $report = $this->repository->create([
                'status' => $attributes['status'],
                'appointment_id' => $appointment_id,
                'user_id' => $appointment->user_id,
                'project_id' => $appointment->project_id,
            ]);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('report.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('report/'.$report->id ))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
    }
    public function show(Request $request,Report $report)
    {
        if ($report->exists) {
            $view = 'report.show';
        } else {
            $view = 'report.new';
        }

        return $this->response->title(trans('report.name'))
            ->data(compact('report'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Report $report)
    {
        try {
            $attributes = $request->all();

            $report->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('report.name')]))
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
    public function destroy(Request $request,Report $report)
    {
        try {
            $report->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('report.name')]))
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

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('report.name')]))
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