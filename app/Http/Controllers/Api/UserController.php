<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Repositories\Eloquent\ArchiveRepository;
use App\Repositories\Eloquent\PageRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\WXBizDataCryptService;
use App\Services\AmapService;
use Log;

class UserController extends BaseController
{
    public function __construct(ArchiveRepository $archiveRepository)
    {
        parent::__construct();
        $this->middleware('auth.api');
        $this->archiveRepository = $archiveRepository;
        $this->user = User::getUser();
    }
    public function getUser(Request $request)
    {
        return $this->response->success()->data($this->user)->json();
    }
    public function submitPhone(Request $request)
    {
        $user = User::getUser();
        $encryptedData = $request->input('encryptedData');
        $iv = $request->input('iv');

        $WXBizDataCryptService = new WXBizDataCryptService($user['session_key']);

        $data = [];
        $errCode = $WXBizDataCryptService->decryptData($encryptedData, $iv, $data );

        if ($errCode != 0) {
            return response()->json([
                'code' => '400',
                'message' => $errCode,
            ]);
        }

        $phone_data = json_decode($data);

        $phone = $phone_data->phoneNumber;

        User::where('id',$user->id)->update([
            'phone' => $phone
        ]);
        return response()->json([
            'code' => '200',
            'message' => '提交成功',
            'data' => $phone
        ]);
    }
    public function submitLocation(Request $request)
    {
        $user = User::getUser();
        $longitude = $request->input('longitude','');
        $latitude =  $request->input('latitude','');
        $amap_service = new AmapService();

        $data = $amap_service->geocode_regeo($longitude.','.$latitude);

        User::where('id',$user->id)->update([
            'longitude' => $longitude,
            'latitude' => $latitude,
            'city' => $data['regeocode']['addressComponent']['city'],
        ]);

        return response()->json([
            'code' => '200',
            'message' => '提交成功',
            'data' => $data['regeocode']['addressComponent']['city'],
        ]);
    }

    public function getArchive(Request $request)
    {
        $archive = $this->archiveRepository->where('user_id',$this->user->id)->first();
        return $this->response->success()->data($archive)->json();
    }
    public function storeArchive(Request $request)
    {
        try{
            $data = $request->all();
            $rule = [
                'name' => 'required',
                'phone' => 'required|regex:'.config('regex.phone'),
                'idcard' => 'required',
                'email' => 'required|email',
            ];
            validateCustomParameter($data,$rule,[
                'name.required' => trans('user.label.name'). " 必填",
                'phone.required' => trans('user.label.phone'). " 必填",
                'phone.regex' => trans('user.label.phone'). " 格式不正确",
                'idcard.required' => trans('user.label.idcard'). " 必填",
                'email.required' => trans('user.email'). " 必填",
                'email.email' => trans('user.email'). " 不正确",
            ]);
            $archive = $this->archiveRepository->where('user_id',$this->user->id)->first();
            if($archive)
            {
                $this->archiveRepository->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'idcard' => $request->idcard,
                    'email' => $request->email,
                ],$archive->id);
                throw new \App\Exceptions\RequestSuccessException("更新成功！");
            }else{
                $this->archiveRepository->create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'idcard' => $request->idcard,
                    'email' => $request->email,
                ]);
                throw new \App\Exceptions\RequestSuccessException("创建成功！");
            }
        }catch (Exception $e) {
            throw new \App\Exceptions\RequestSuccessException("操作失败！");
        }

    }
}
