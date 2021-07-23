<?php


return [
    'access_key'        => env('ALIYUN_SMS_AK'), // accessKey
    'access_secret'     => env('ALIYUN_SMS_AS'), // accessSecret
    'sign_name'         => env('ALIYUN_SMS_SIGN_NAME'), // 签名
    'appointment_success_sms' => 'SMS_220545132', // 预约成功通知
];
