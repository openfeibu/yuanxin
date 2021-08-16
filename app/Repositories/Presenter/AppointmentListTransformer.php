<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;
use DB;

class AppointmentListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Appointment $appointment)
    {
        $project = $appointment->project->toArray();
        $report =  $appointment->report;
        return [
            'id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'report_id' => $report ? $report->id : 0,
            'number' => $appointment->number,
            'name' => $appointment->name,
            'phone' => $appointment->phone,
            'idcard' => $appointment->idcard,
            'status' => $appointment->status,
            'status_desc' => trans('appointment.status.'.$appointment->status),
            'project' => $project,
            'date' => date('Y年m月d日',strtotime($appointment->date)),
            'start_time' => substr($appointment->start_time,0,5),
            'end_time' => substr($appointment->end_time,0,5),
        ];
    }
}
