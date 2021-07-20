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
        $this->middleware('auth.api',['except' => ['getAppointmentDates']]);
        $this->appointmentRepository = $appointmentRepository;
        $this->appointmentDateRepository = $appointmentDateRepository;
        $this->projectRepository = $projectRepository;
        $this->archiveRepository = $archiveRepository;
        $this->projectRepository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function getAppointments(Request $request)
    {
        $user = User::tokenAuth();
        $limit = $request->input('limit',config('app.limit'));
        $appointments = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$user->id)
            ->orderBy('id','desc')
            ->getDataTable($limit);
        return $this->response->success()->data($appointments['data'])->count($appointments['recordsTotal'])->json();
    }
    public function getAppointment(Request $request,$id)
    {
        $user = User::tokenAuth();
        $appointment = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$user->id)
            ->find($id);
        return $this->response->success()->data($appointment['data'])->json();
    }
    public function storeAppointment(Request $request)
    {
        $user = User::tokenAuth();
        $data = $request->all();
        $rule = [
            'date' => 'required',
            'appointment_date_id' => 'required|exists:appointment_dates,id',
            'project_id' => 'required|exists:projects,id',
            'archive_id' => 'required|exists:archives,id',
        ];
        validateCustomParameter($data,$rule,[
            'appointment_date_id.exists' => '该时间段不能预约',
            'project_id.exists' => '项目不存在',
            'archive_id.exists' => '健康档案不存在',
        ]);
        $appointment_date = $this->appointmentDateRepository->find($request->appointment_date_id);
        if($request->date < date('Y-m-d'))
        {
            throw new OutputServerMessageException('时间不能小于今天');
        }
        if($appointment_date->start_time.":00" < date('H:i:s'))
        {
            throw new OutputServerMessageException('时间错误');
        }
        $appointed_count = $this->appointmentRepository->where([
            'date' => $request->date,
            'start_time' => $appointment_date['start_time'],
        ])->count();
        if($appointment_date->count - $appointed_count <= 0)
        {
            throw new OutputServerMessageException('该时间段已预约满');
        }
        $archive = $this->archiveRepository->find($request->archive_id);
        //$project = $this->projectRepository->setPresenter(\App\Repositories\Presenter\Api\ProjectShowPresenter::class)->find($request->project_id);
        $appointment = $this->appointmentRepository->create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $appointment_date->start_time,
            'end_time' => $appointment_date->end_time,
            'project_id' => $request->project_id,
            'name' => $archive->name,
            'phone' => $archive->phone,
            'idcard' => $archive->idcard,
        ]);
        $appointment = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$user->id)
            ->find($appointment->id);
        return $this->response->success()->message("预约成功！")->data($appointment['data'])->json();
    }
    public function getAppointmentDates(Request $request)
    {
        $appointment_dates = $this->appointmentDateRepository->setPresenter(\App\Repositories\Presenter\Api\AppointmentDatePresenter::class)
            ->orderBy('start_time','asc')
            ->get()['data'];
        foreach ($appointment_dates as $key => $appointment_date)
        {
            $appointed_count = $this->appointmentRepository->where([
                'date' => $request->date,
                'start_time' => $appointment_date['start_time'],
            ])->count();
            $appointment_dates[$key]['remaining_count'] = $appointment_date['count'] - $appointed_count;

        }
        return $this->response->success()->data($appointment_dates)->json();
    }
}
