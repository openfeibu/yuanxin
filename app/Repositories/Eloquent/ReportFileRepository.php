<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\ReportFileRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ReportFileRepository extends BaseRepository implements ReportFileRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.report.report_file.search');
    }
    public function model()
    {
        return config('model.report.report_file.model');
    }

}