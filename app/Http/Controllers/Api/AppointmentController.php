<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OutputServerMessageException;
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
        ProjectRepository $projectRepository,
        ArchiveRepository $archiveRepository)
    {
        parent::__construct();
        $this->middleware('auth.api');
        $this->appointmentRepository = $appointmentRepository;
        $this->projectRepository = $projectRepository;
        $this->archiveRepository = $archiveRepository;
        $this->projectRepository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function getAppointments(Request $request)
    {

    }
    public function getAppointment(Request $request,$id)
    {
        $appointment = $this->appointmentRepository->find($id);
        return $this->response->success()->data($appointment)->json();
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
        $project = $this->projectRepository->setPresenter(\App\Repositories\Presenter\Api\ProjectShowPresenter::class)->find($request->project_id);
        $this->appointmentRepository->create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'project_id' => $request->project_id,
            'name' => $archive->name,
            'phone' => $archive->phone,
            'idcard' => $archive->idcard,
        ]);
        return $this->response->success()->message("预约成功！")->data([
            'project' => $project['data'],
            'date' => date('m月d日',strtotime($request->date)) . $request->start_time,
            'name' => $archive->name,
            'phone' => $archive->phone,
            'idcard' => $archive->idcard,
        ])->json();
    }
}
