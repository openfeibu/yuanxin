<?php

namespace App\Repositories\Presenter;

use League\Fractal\TransformerAbstract;

class PageListTransformer extends TransformerAbstract
{
    public function transform(\App\Models\Page $page)
    {
        return [
            'id'      => $page->id,
            'slug'    => $page->slug,
            'url'     => $page->slug . '.html',
            'name'    => $page->name,
            'image'   => $page->image ? url("/image/sm".$page->image) : '',
            'heading' => $page->heading,
            'title'   => $page->title,
            'keyword' => $page->keyword,
            'status'  => $page->status,
            'order'   => $page->order,
            'home_recommend' => $page->recommend_type == 'home' ? true : false,
            'category_id' => $page->category_id,
            'created_at' => format_date($page->created_at,'Y-m-d H:i:s'),
            'updated_at' => format_date($page->updated_at,'Y-m-d H:i:s'),
            'category_name' => $page->category->name,
        ];
    }
}
