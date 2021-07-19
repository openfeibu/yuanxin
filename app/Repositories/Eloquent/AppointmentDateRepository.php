<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AppointmentDateRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AppointmentDateRepository extends BaseRepository implements AppointmentDateRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.appointment_date.appointment_date.search');
    }
    public function model()
    {
        return config('model.appointment_date.appointment_date.model');
    }

}