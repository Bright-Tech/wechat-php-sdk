<?php
namespace bright_tech\wechat\response;

use bright_tech\wechat\core\Response;

class WebAuthAccessTokenResponse extends Response
{

    public $accessToken;

    public $expiresIn;

    public $refreshToken;

    public $openId;

    public $scope;

    public function afterBuild()
    {
        $this->accessToken = $this->convertedResponse['access_token'];
        $this->expiresIn = $this->convertedResponse['expires_in'];
        $this->refreshToken = $this->convertedResponse['refresh_token'];
        $this->openId = $this->convertedResponse['openid'];
        $this->scope = $this->convertedResponse['scope'];
    }
}

