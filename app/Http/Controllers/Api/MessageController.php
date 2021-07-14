<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Repositories\Eloquent\MessageRepository;
use App\Models\Message;
use App\Models\User;
use App\Services\ImageService;
use Auth,Filer,Input,Validator;

class MessageController extends BaseController
{
    public function __construct(MessageRepository $message)
    {
        parent::__construct();
        $this->repository = $message;
        $this->user = User::getUser();
    }
    public function getMessages(Request $request)
    {
        $messages = $this->repository
            ->where('user_id',$this->user->id)
            ->orderBy('id','desc')
            ->paginate(20);
        foreach ($messages as $key => $message)
        {
            if($message->type != 'text')
            {
                $message->content = url('image/original'.$message->content);
            }
            $message->admin_user_avatar = $message->admin_user_avatar;
        }
        return $this->response
            ->data([
                'messages' => $messages->toArray()['data']
            ])
            ->json();
    }
    public function storeMessage(Request $request)
    {
        try {
            // 规则
            $rules = [
                'type' => [
                    'required',
                    Rule::in(['text','image','video','file']),
                ],
                'content' => 'required_if:type,text'
            ];
            $messages = [
                'type.require' => '类型不能为空',
                'type.in' => '类型不正确',
            ];
            $validator = Validator::make($request->all(), $rules,$messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => '400',
                    "message" =>$validator->errors()->first()
                ],400);
            }
            $type = $request->get('type','');
            $config = 'model.message.message';
            $url = '';
            $content = '';
            switch ($type)
            {
                case 'text':
                    $content = $request->get('content','');
                    break;
                case 'image':
                    $array = app(ImageService::class)->uploadImages(Input::all(),config($config . '.upload_folder'));
                    $url = url('image/original' . '/' . ltrim($array['image_url'][0],'/'));
                    $content = $array['image_url'][0];
                    break;
                case 'video':
                    break;
                case 'file':
                    $array = app(ImageService::class)->uploadFiles(Input::all(),config($config . '.upload_folder'));
                    $url = url('image/original' . '/' . ltrim($array['file_url'][0],'/'));
                    $content = $array['file_url'][0];
                    break;
            }
            $this->repository->create([
                'user_id' => $this->user->id,
                'type' => $type,
                'content' => $content
            ]);
            return $this->response
                ->data(compact('type','url','content'))
                ->json();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->json();
        }
    }

}