<?php

namespace App\Repositories\Presenter\Api;

use League\Fractal\TransformerAbstract;
use DB;

class AppointmentListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Appointment $appointment)
    {
        $project = $appointment->project->toArray();
        $project['image'] = handle_image_url($project['image'],config('app.image_url').'/image/original');
        return [
            'id' => $appointment->id,
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
