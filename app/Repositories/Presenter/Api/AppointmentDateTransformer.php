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
            'start_time' => $appointment_date->start_time,
            'end_time' => $appointment_date->end_time,
            'count' => $appointment_date->count,
        ];
    }
}
