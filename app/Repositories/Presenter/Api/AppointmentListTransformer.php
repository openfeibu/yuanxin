<?php

namespace App\Repositories\Presenter\Api;

use League\Fractal\TransformerAbstract;
use DB;

class AppointmentListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Appointment $appointment)
    {
        return [
            'id' => $appointment->id,
            'name' => $appointment->name,
            'phone' => $appointment->phone,
            'idcard' => $appointment->idcard,
        ];
    }
}
