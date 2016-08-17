<?php

class Weixin
{
    private $appid = 'wx3eae1258010cfa23';
    private $secret = '9a880e160b566c145cf56815dd4f4910';

    public function curl( $url )
    {
        $ch = curl_init();
        // set url
        curl_setopt( $ch, CURLOPT_URL, $url );

        // return the transfer as a string
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        // $output contains the output string
        $output = curl_exec( $ch );

        // close curl resource to free up system resources
        curl_close( $ch );
        return json_decode( $output, true );
    }
    /**
     * 获取access token
     *
     * access_token是公众号的全局唯一票据，公众号调用各接口时都需使用access_token。开发者需要进行妥善保存。
     * access_token的存储至少要保留512个字符空间。
     * access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
     */
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
        return $this->curl( $url );
    }

    /**
     * 通过code换取网页授权access_token
     */
    public function getAuthAccessTokenByCode( $code )
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code={$code}&grant_type=authorization_code";
        return $this->curl( $url );
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     *
     * @param unknown $authAccessToken
     * @param unknown $openid
     */
    public function getUserinfoByAuthAccessToken( $authAccessToken, $openid )
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$authAccessToken}&openid={$openid}&lang=zh_CN";
        return $this->curl( $url );
    }

    public function getJSapiTicket( $accessToken )
    {
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accessToken}&type=jsapi";
        return $this->curl( $url );
    }

    public function getJSSDKSignature()
    {
        $noncestr = 'Wm3WZYTPz0wzccnW';
        $timestamp = time();
    }
}

