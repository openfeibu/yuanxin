<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;

class BannerListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Banner $banner)
    {
        return [
            'id' => $banner->id,
            'title' =>$banner->title,
            'image' => $banner->image ? url("/image/sm".$banner->image) : '',
            'url' => $banner->url,
            'type_desc' => trans('banner.type.'.$banner->type),
            'order' => $banner->order,
        ];
    }
}
