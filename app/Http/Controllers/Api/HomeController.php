<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Eloquent\PageRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Banner;
use App\Models\Setting;
use Log;
use Mail;

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
    public function test()
    {
        $email = '1270864834@qq.com';
        $html = "<div class='1'>您好，请明天九点前过来上班</div>";
        $send = Mail::html($html, function($message) use($email) {
            $message->from(config('mail.from')['address'],config('mail.from')['name']);
            $message->subject('['.config('app.name').'] 邀请好友');
            $message->to($email);
        });
        var_dump($send);exit;

    }
}
