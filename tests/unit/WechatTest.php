<?php


use bright_tech\wechat\Wechat;

class WechatTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var Wechat
     */
    protected $wechat;
    protected $accessToken;
    protected $expired;
    protected $openId = 'oHK7_v7eRSUKbpT16siv9HG7vx4U';

    public function getWechatClient()
    {
        if (!$this->wechat) {
            $this->wechat = new Wechat('wxca0c30dff2f7c909', '3a872608bc54b6ff4ab1c3118ad4e9cc');
        }
        return $this->wechat;
    }

    public function getAccessToken()
    {
        if (!$this->accessToken || $this->expired > time()) {
            $response = $this->getWechatClient()->getAccessToken();
            $this->assertNotEmpty($response->accessToken, 'getAccessToken 失败');
            $this->accessToken = $response->accessToken;
            $this->expired = time() + $response->expiresIn;
        }
        return $this->accessToken;
    }

    protected function _before()
    {

    }

    protected function _after()
    {
    }

//    public function testGetCode(){
//
//    }

    // tests
    public function testMessage()
    {
        $this->assertNotEmpty(null);
        $wechat = $this->getWechatClient();
        $model = new \bright_tech\wechat\models\TemplateMessage();
        $model->templateId = 'KCmuZWBsCrbJxZFAHDntO7OmBxezyUGQXEhXPtsorOo';
        $model->touser = $this->openId;
        $model->data = [
            "first" => ['value' => '您好，学院奖通知如下：'],
            'keyword1' => '广告人',
            'keyword2' => '广告人',
            'keyword3' => '广告人',
            'keyword4' => '广告人',
        ];
        $response = $wechat->sendTemplateMessage($this->getAccessToken(), $model);
        $this->assertNotEmpty($response->msgid);
    }

    public function testGetQrcode()
    {
        $wechat = $this->getWechatClient();
        $model = new \bright_tech\wechat\models\Qrcode();
        $model->expireSeconds = 1800;
        $model->actionName = 'QR_SCENE';
        $model->actionInfo = [
            'scene' =>
                [
                    'scene_id' => 123
                ]
        ];
        $response = $wechat->createQrCode($this->getAccessToken(), $model);
        $this->assertNotEmpty($response->url);
        codecept_debug('QrCode Ticket: ' . $response->ticket);
    }
}