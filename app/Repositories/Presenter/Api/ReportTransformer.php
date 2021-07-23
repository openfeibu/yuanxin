<?php

namespace App\Repositories\Presenter\Api;

use League\Fractal\TransformerAbstract;
use DB;

class ReportTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Report $report)
    {
        $project = $report->project->toArray();
        $appointment = $report->appointment;
        $project['image'] = handle_image_url($project['image'],config('app.image_url').'/image/original');
        return [
            'id' => $report->id,
            'title' => $project['name'].'报告单',
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
