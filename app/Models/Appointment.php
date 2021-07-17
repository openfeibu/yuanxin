<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Appointment extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.appointment.appointment';

    protected $appends = ['number'];

    public function getNumberAttribute()
    {
        $number =  $this->attributes['id'] ? ($this->attributes['id'] < 10000 ? sprintf("%05d", $this->attributes['id']) : $this->attributes['id']) : '';
        $number = 'YX'.$number;
        return $number;
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}