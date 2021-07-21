<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OutputServerMessageException;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\ReportDateRepository;
use App\Repositories\Eloquent\ReportFileRepository;
use App\Repositories\Eloquent\ReportRepository;
use App\Repositories\Eloquent\ArchiveRepository;
use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Log;
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
            $report_file->url = $report_file->url ? handle_image_url($report_file->url,config('app.url').'/image/download') : '';
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

}
