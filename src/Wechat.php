<?php
namespace bright_tech\wechat;

use bright_tech\wechat\core\Http;
use bright_tech\wechat\core\Request;

/**
 *
 * @author SamXiao
 *
 */
class Wechat
{

    protected $wechatEndPoint = 'https://api.weixin.qq.com/cgi-bin';

    protected $appid = '';

    protected $secret = '';

    protected $request;

    public function __construct($appid, $secret)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }

    public function getRequest()
    {
        if (! $this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        $request = $this->getRequest();
        $response = $request->doGet($this->wechatEndPoint . '/token', [
            'grant_type' => 'client_credential',
            'appid' => $this->appid,
            'secret' => $this->secret
        ]);
        return $response;
    }
}

