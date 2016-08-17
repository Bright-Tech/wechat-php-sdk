<?php
namespace bright_tech\wechat;

use bright_tech\wechat\core\Request;
use bright_tech\wechat\response\AccessTokenResponse;

/**
 *
 * @author SamXiao
 *
 */
class Wechat
{

    /**
     *
     * @var string
     */
    protected $wechatEndPoint = 'https://api.weixin.qq.com/cgi-bin';

    /**
     *
     * @var string
     */
    protected $appid = '';

    /**
     *
     * @var string
     */
    protected $secret = '';

    /**
     *
     * @var unknown
     */
    protected $request;

    /**
     *
     * @param unknown $appid
     * @param unknown $secret
     */
    public function __construct($appid, $secret)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }

    /**
     *
     * @return \bright_tech\wechat\core\Request
     */
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
            'grant_type' => '',
            'appid' => $this->appid,
            'secret' => $this->secret
        ]);
        return new AccessTokenResponse($response);
    }
}

