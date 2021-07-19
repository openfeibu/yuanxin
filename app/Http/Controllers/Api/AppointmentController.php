<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OutputServerMessageException;
use App\Repositories\Eloquent\AppointmentDateRepository;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ArchiveRepository;
use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Log;
use Auth;
use App\Models\User;

class AppointmentController extends BaseController
{
    public function __construct(
        AppointmentRepository $appointmentRepository,
        AppointmentDateRepository $appointmentDateRepository,
        ProjectRepository $projectRepository,
        ArchiveRepository $archiveRepository)
    {
        parent::__construct();
        $this->middleware('auth.api');
        $this->user = User::getUser();
        $this->appointmentRepository = $appointmentRepository;
        $this->appointmentDateRepository = $appointmentDateRepository;
        $this->projectRepository = $projectRepository;
        $this->archiveRepository = $archiveRepository;
        $this->projectRepository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function getAppointments(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $appointments = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$this->user->id)
            ->orderBy('id','desc')
            ->getDataTable($limit);
        return $this->response->success()->data($appointments['data'])->count($appointments['recordsTotal'])->json();
    }
    public function getAppointment(Request $request,$id)
    {
        $appointment = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$this->user->id)
            ->find($id);
        return $this->response->success()->data($appointment['data'])->json();
    }
    public function storeAppointment(Request $request)
    {
        $user = User::tokenAuth();
        $data = $request->all();
        $rule = [
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'project_id' => 'required|exists:projects,id',
            'archive_id' => 'required|exists:archives,id',
        ];
        validateCustomParameter($data,$rule,[
            'project_id.exists' => '项目不存在',
            'archive_id.exists' => '健康档案不存在',
        ]);
        if($request->date < date('Y-m-d'))
        {
            throw new OutputServerMessageException('时间不能小于今天');
        }
        if($request->start_time.":00" < date('H:i:s'))
        {
            throw new OutputServerMessageException('时间错误');
        }
        if($request->end_time < $request->start_time)
        {
            throw new OutputServerMessageException('时间错误');
        }
        $archive = $this->archiveRepository->find($request->archive_id);
        //$project = $this->projectRepository->setPresenter(\App\Repositories\Presenter\Api\ProjectShowPresenter::class)->find($request->project_id);
        $appointment = $this->appointmentRepository->create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'project_id' => $request->project_id,
            'name' => $archive->name,
            'phone' => $archive->phone,
            'idcard' => $archive->idcard,
        ]);
        $appointment = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$this->user->id)
            ->find($appointment->id);
        return $this->response->success()->message("预约成功！")->data($appointment['data'])->json();
    }
    public function getAppointmentDates()
    {
        $appointment_dates = 1;
    }
}
