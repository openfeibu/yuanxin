<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SmsRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SmsRepository extends BaseRepository implements SmsRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.sms.sms.search');
    }
    public function model()
    {
        return config('model.sms.sms.model');
    }

}