<?php

namespace App\Repositories\Presenter\Api;

use League\Fractal\TransformerAbstract;
use DB;

class AppointmentDateTransformer extends TransformerAbstract
{
    public function transform(\App\Models\AppointmentDate $appointment_date)
    {
        return [
            'id' => $appointment_date->id,
            'start_time' => substr($appointment_date->start_time,0,5),
            'end_time' => substr($appointment_date->end_time,0,5),
            'count' => $appointment_date->count,
        ];
    }
}
