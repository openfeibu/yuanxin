<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;
use DB;

class ReportFileTransformer extends TransformerAbstract
{
    public function transform(\App\Models\ReportFile $report_file)
    {
        return [
            'id' => $report_file->id,
            'name' => $report_file->name,
            'url' => $report_file->url,
            'original_url' => $report_file->file_type == 'video' ? handle_image_url($report_file->url,config('app.image_url').'/image/download'): handle_image_url($report_file->url,config('app.image_url').'/image/original'),
            'suffix' => $report_file->suffix,
            'file_type' => $report_file->file_type,
            'file_type_desc' => trans('report_file.file_type.'.$report_file->file_type),
            'created_at' => format_date($report_file->created_at,'Y-m-d H:i:s'),
            'updated_at' => format_date($report_file->updated_at,'Y-m-d H:i:s'),
        ];
    }
}
