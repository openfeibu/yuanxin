<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Report extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.report.report';

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}