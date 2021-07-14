<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Eloquent\PageCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;

class PageCategoryController extends BaseController
{
    public function __construct(PageCategoryRepository $category)
    {
        parent::__construct();
        $this->repository = $category;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class)
            ->pushCriteria(\App\Repositories\Criteria\PageCategoryResourceCriteria::class);
    }
    public function getPageCategories(Request $request)
    {
        $pslug = $request->input('pslug');
        $parent_id = $request->input('parent_id',0);
        if($pslug)
        {
            $pcate = $this->repository
                ->Where(['slug' => $pslug])
                ->first();
            $parent_id = $pcate->id;
        }
        $data = $this->repository
            ->orderBy('order','asc')
            ->findWhere(['parent_id' => $parent_id]);

        return $this->response->success()->data($data)->json();
    }

}
