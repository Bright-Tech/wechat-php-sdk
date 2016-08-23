<?php


use bright_tech\wechat\Wechat;

class MessageTest extends \Codeception\Test\Unit
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
            $this->wechat = new Wechat('wx3eae1258010cfa23', '9a880e160b566c145cf56815dd4f4910');
        }
        return $this->wechat;
    }

    public function getAccessToken()
    {
        if (!$this->accessToken || $this->expired > time()) {
            $response = $this->getWechatClient()->getAccessToken();
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
        print_r($response);
        $this->assertNotEmpty($response->msgid);
    }
}