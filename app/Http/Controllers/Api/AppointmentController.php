<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OutputServerMessageException;
use App\Models\Sms;
use App\Repositories\Eloquent\AppointmentDateRepository;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ArchiveRepository;
use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Log;
use Auth;
use Mail;
use App\Models\User;
use Mrgoon\AliSms\AliSms;

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
//        $smses = Sms::get()->toArray();
//        foreach ($smses as $key => $sms)
//        {
//            $smses[$key]['body'] = json_decode($sms['body']);
//        }
//        var_dump($smses);exit;
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
        if($request->date == date('Y-m-d') && $appointment_date->start_time.":00" < date('H:i:s'))
        {
            throw new OutputServerMessageException('该时间段已经无法预约');
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
            'note' => $request->note ?? '',
            'start_time' => $appointment_date->start_time,
            'end_time' => $appointment_date->end_time,
            'project_id' => $request->project_id,
            'name' => $archive->name,
            'phone' => $archive->phone,
            'idcard' => $archive->idcard,
        ]);
        $code = get_appointment_code();
        $number = 'YX'.($appointment->id < 10000 ? sprintf("%05d", $appointment->id) : $appointment->id);
        $appointment->update(['number' => $number,'code' => $code]);
        $appointment = $this->appointmentRepository
            ->setPresenter(\App\Repositories\Presenter\Api\AppointmentListPresenter::class)
            ->where('user_id',$user->id)
            ->find($appointment->id);
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($archive->phone, config('aliyunsms.appointment_success_sms'), [
            'name'=> $appointment['data']['name'],
            'date'=> $appointment['data']['date'].$appointment['data']['start_time'],
            'project_name'=> $appointment['data']['project']['name'],
            'code'=> $code,
        ]);
        Sms::create([
            'code' => config('aliyunsms.appointment_success_sms'),
            'name' => '预约成功后通知',
            'body' => json_encode($response),
            'smsable_id' => $appointment['data']['id'],
            'smsabletype_id' => 'App\Models\Appointment'
        ]);
        $html = "<div class='1'>您好，有新的预约，请注意查看！<a href='".config('app.url')."/admin/appointment' target='_blank'>管理后台</a>";
        $html .="<p>姓名：".$appointment['data']['name']."</p>";
        $html .="<p>项目名称：".$appointment['data']['project']['name']."</p>";
        $html .="<p>预约日期：".$appointment['data']['date'].$appointment['data']['start_time']."</p>";
        $email = setting('notice_email');
        $send = Mail::html($html, function($message) use($email) {
            $message->from(config('mail.from')['address'],config('mail.from')['name']);
            $message->subject('[预约]');
            $message->to($email);
        });

        return $this->response->success()->message("预约成功！")->data($appointment['data'])->json();
    }
    public function getAppointmentDates(Request $request)
    {
        $dates = get_future_days('','Y-m-d',7,true);

        $appointment_times = $this->appointmentDateRepository->setPresenter(\App\Repositories\Presenter\Api\AppointmentDatePresenter::class)
            ->orderBy('start_time','asc')
            ->get()['data'];
        $full_dates = [];
        foreach ($dates as $key => $date)
        {
            foreach ($appointment_times as $key => $appointment_time)
            {
                $appointed_count = $this->appointmentRepository->where([
                    'date' => $date,
                    'start_time' => $appointment_time['start_time'],
                ])->count();
                $appointment_times[$key]['remaining_count'] = $appointment_time['count'] - $appointed_count;
                $appointment_times[$key]['available'] = 1;
                if($appointment_times[$key]['remaining_count'] == 0 || ($date == date('Y-m-d') && $appointment_time['start_time'] < date('H:i')))
                {
                    $appointment_times[$key]['available'] = 0;
                }
            }
            $full_dates[$date] = $appointment_times;
        }

        return $this->response->success()->data([
            'dates' => $dates,
            'full_dates' => $full_dates,
        ])->json();
    }
}
