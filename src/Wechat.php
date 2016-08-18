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
    protected $wechatEndPoint = 'https://api.weixin.qq.com';

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
     * @var Request
     */
    protected $request;

    /**
     *
     * @param string $appid
     * @param string $secret
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
        $response = $request->doGet($this->wechatEndPoint . '/cgi-bin/token', [
            'grant_type' => '',
            'appid' => $this->appid,
            'secret' => $this->secret
        ]);
        return new AccessTokenResponse($response);
    }

    /**
     * 网页授权access_token
     */
    public function getWebAuthAccessToken(){
        $request = $this->getRequest();
        $response = $request->doGet($this->wechatEndPoint . '/sns/oauth2/access_token', [
            'grant_type' => '',
            'appid' => $this->appid,
            'secret' => $this->secret
        ]);
        return new AccessTokenResponse($response);
    }
}

