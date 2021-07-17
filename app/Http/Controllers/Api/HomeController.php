<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Eloquent\PageRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Banner;
use App\Models\Setting;
use Log;

class HomeController extends BaseController
{
    public function __construct(PageRepositoryInterface $page)
    {
        parent::__construct();
        $this->repository = $page;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class)
            ->pushCriteria(\App\Repositories\Criteria\PageResourceCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $data = $this->repository
            ->setPresenter(\App\Repositories\Presenter\PageListPresenter::class)
            ->getDataTable($limit);

        return [
            'code' => '200',
            'data' => $data,
        ];
    }
    public function getBanners(Request $request)
    {
        $banners = Banner::orderBy('order','asc')->orderBy('id','asc')->get();
        foreach ($banners as $key => $val)
        {
            $banners[$key]['image'] = config('app.image_url').'/image/original'.$val['image'];
        }
        return $this->response->success()->data($banners)->json();
    }
     public function contact()
     {
         return $this->response->success()->data([
             'company' => setting('company_name'),
             'longitude' => setting('longitude'),
             'latitude' => setting('latitude'),
             'address' => setting('address'),
             'tel' => setting('tel'),
         ])->json();
     }
}
