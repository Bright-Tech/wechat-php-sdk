<?php
namespace bright_tech\wechat;

use bright_tech\wechat\core\Request;
use bright_tech\wechat\models\TemplateMessage;
use bright_tech\wechat\response\AccessTokenResponse;
use bright_tech\wechat\response\WebAuthAccessTokenResponse;

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
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }


    /**
     * @return AccessTokenResponse
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
     * 通过code换取网页授权access_token
     *
     * 这里通过code换取的是一个特殊的网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同。
     * 如果网页授权的作用域为snsapi_base，则本方法中获取到网页授权access_token的同时，也获取到了openid，snsapi_base式的网页授权流程即到此为止。
     *
     * @param string $code
     * @return WebAuthAccessTokenResponse
     */
    public function getAuthAccessToken($code)
    {
        $request = $this->getRequest();
        $response = $request->doGet($this->wechatEndPoint . '/sns/oauth2/access_token', [
            'grant_type' => '',
            'appid' => $this->appid,
            'secret' => $this->secret,
            'code' => $code
        ]);
        return new WebAuthAccessTokenResponse($response);
    }

    public function sendTemplateMessage($accessToken, TemplateMessage $templateMessage)
    {
        $request = $this->getRequest();
        $response = $request->doPost($this->wechatEndPoint . '/message/template/send?access_token=' . $accessToken, $templateMessage);
        return new WebAuthAccessTokenResponse($response);
    }
}

