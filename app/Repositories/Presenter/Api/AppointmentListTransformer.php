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
            'project' => $project,
            'date' => date('Y年m月n日',strtotime($appointment->date)),
            'start_time' => substr($appointment->start_time,0,5),
        ];
    }
}
