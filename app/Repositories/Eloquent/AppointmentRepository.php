<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AppointmentRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.appointment.appointment.search');
    }
    public function model()
    {
        return config('model.appointment.appointment.model');
    }

}