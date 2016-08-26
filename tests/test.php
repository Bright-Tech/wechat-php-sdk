<?php
require_once __DIR__ . '/../autoload.php';
use bright_tech\wechat\Wechat;


$wechat = new Wechat('', '');

try {
    $result = $wechat->getAccessToken();

    $model = new \bright_tech\wechat\models\TemplateMessage();

    $model->templateId = 'KCmuZWBsCrbJxZFAHDntO7OmBxezyUGQXEhXPtsorOo';
    $model->touser = 'oHK7_v7eRSUKbpT16siv9HG7vx4U';
    $model->data = [
        "first" => ['value' => '您好，学院奖通知如下：'],
        'keyword1' => ['value' => '广告人1'],
        'keyword2' => ['value' => '广告人2'],
        'keyword3' => ['value' => '广告人3'],
        'keyword4' => ['value' => '广告人4'],
        'remark' =>['value' => '广告人']
    ];

    echo  '<div>' . $model->toJson() . '</div>';
    $response = $wechat->sendTemplateMessage($result->accessToken, $model);
    print_r($response);
}catch (\Exception $e){
    echo 'Error';
    print_r($e);
}
