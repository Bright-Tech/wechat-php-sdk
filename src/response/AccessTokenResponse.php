<?php
namespace bright_tech\wechat\response;

use bright_tech\wechat\core\Response;

class AccessTokenResponse extends Response
{

    public $accessToken;

    public $expiresIn;

    public function afterBuild()
    {
        $this->accessToken = $this->convertedResponse['access_token'];
        $this->expiresIn = $this->convertedResponse['expires_in'];
    }
}

