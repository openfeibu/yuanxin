<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OutputServerMessageException;
use App\Exceptions\RequestSuccessException;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ReportDateRepository;
use App\Repositories\Eloquent\ReportFileRepository;
use App\Repositories\Eloquent\ReportRepository;
use App\Repositories\Eloquent\ArchiveRepository;
use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Log;
use Mail;
use Auth;
use App\Models\User;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReportController extends BaseController
{
    public function __construct(
        AppointmentRepository $appointmentRepository,
        ProjectRepository $projectRepository,
        ReportRepository $reportRepository,
        ReportFileRepository $reportFileRepository)
    {
        parent::__construct();
        $this->middleware('auth.api');
        $this->appointmentRepository = $appointmentRepository;
        $this->projectRepository = $projectRepository;
        $this->reportRepository = $reportRepository;
        $this->reportFileRepository = $reportFileRepository;
        $this->reportRepository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function getReports(Request $request)
    {
        $user = User::tokenAuth();
        $limit = $request->input('limit',config('app.limit'));
        $reports = $this->reportRepository
            ->setPresenter(\App\Repositories\Presenter\Api\ReportPresenter::class)
            ->where('user_id',$user->id)
            ->orderBy('id','desc')
            ->getDataTable($limit);
        return $this->response->success()->data($reports['data'])->count($reports['recordsTotal'])->json();
    }
    public function getReport(Request $request,$id)
    {
        $user = User::tokenAuth();
        $report = $this->reportRepository
            ->setPresenter(\App\Repositories\Presenter\Api\ReportPresenter::class)
            ->where('user_id',$user->id)
            ->find($id);

        $report_files = $this->reportFileRepository->where('report_id',$id)->orderBy('updated_at','asc')->get();
        $report_files_arr = [];
        foreach ($report_files as $key => $report_file)
        {
            $report_file->url = $report_file->url ? handle_image_url($report_file->url,config('app.image_url').'/image/download') : '';
            if(isset($report_files_arr[$report_file->updated_at->format('Y-m-d')]))
            {
                $data = [];
                $data[] = $report_file->toArray();
                $report_files_arr[$report_file->updated_at->format('Y-m-d')] = array_merge($report_files_arr[$report_file->updated_at->format('Y-m-d')],$data);
            }else{
                $report_files_arr[$report_file->updated_at->format('Y-m-d')][] = $report_file->toArray();
            }
        }

        return $this->response->success()->data([
            'report' => $report['data'],
            'report_files' => $report_files_arr
        ])->json();
    }
    public function sendMail(Request $request)
    {
        $user = User::tokenAuth();
        $archive = $user->archive;
        $ids = $request->ids;
        $ids = is_array($ids) ? $ids : explode(',',$ids);
        $report_files = $this->reportFileRepository->whereIn('id', $ids)->orderBy('updated_at', 'asc')->get();
        if(!$report_files)
        {
            throw new OutputServerMessageException('请至少选择一项');
        }
        $report_id = $report_files->toArray()[0]['report_id'];
        $report = $this->reportRepository
            ->setPresenter(\App\Repositories\Presenter\Api\ReportPresenter::class)
            ->where('user_id',$user->id)
            ->find($report_id);
        $project_name = $report['data']['project']['name'];
        $email = $archive->email;
        $html = "<div class='1'>您好，".$project_name." 报告单文件请在附件查收！</div>";
        $send = Mail::html($html, function($message) use($email,$report_files,$project_name) {
            $message->from(config('mail.from')['address'],config('mail.from')['name']);
            $message->subject('['.$project_name.' 报告单] ');
            $message->to($email);
            foreach ($report_files as $key => $report_file)
            {
                $message->attach(storage_path('uploads'.$report_file['url']), ['as'=>$report_file['name']]);
            }
        });
        throw new RequestSuccessException('已发送至'.$email.'！可能存在延迟，请注意查收！');
    }
}
