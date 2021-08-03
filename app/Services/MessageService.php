<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use EasyWeChat\Factory;

class MessageService
{

    public function sendSubscribeMessage($type, $data)
    {
        $config = [
            'app_id' => config('wechat.mini_program.default.app_id'),
            'secret' =>  config('wechat.mini_program.default.secret'),

        ];
        $app = Factory::miniProgram($config);
        switch ($type)
        {
            case 'update_report_file':
                $template_id = config('wechat.mini_program.default.template_id.'.$type);
                $template_data = [
                    'thing1' => $data['name'],
                    'thing6' => '您的报告已有新的更新，请及时查看',
                ];
                $page = 'pages/reportDetail/reportDetail?id='.$data['report_id'];
                break;
        }
        $result = $app->subscribe_message->send([
            'touser' => $data['openid'],
            'template_id' => $template_id,
            'page' => $page,
            'data' => $template_data,
        ]);

        return $result;
    }

}