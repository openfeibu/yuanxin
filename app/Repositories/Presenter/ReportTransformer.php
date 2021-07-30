<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;
use DB;

class ReportTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Report $report)
    {
        $project = $report->project->toArray();
        $appointment = $report->appointment;
        return [
            'id' => $report->id,
            'project_name' => $project['name'],
            'name' => $appointment->name,
            'phone' => $appointment->phone,
            'idcard' => $appointment->idcard,
            'status' => $report->status,
            'status_desc' => trans('report.status.'.$report->status),
            'project' => $project,
            'date' =>  $report->updated_at->format('Y-m-d'),
        ];
    }
}
