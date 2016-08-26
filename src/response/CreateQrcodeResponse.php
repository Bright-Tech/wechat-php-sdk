<?php
namespace bright_tech\wechat\response;

use bright_tech\wechat\core\Response;

/**
 * Class CreateQrcodeResponse
 * @package bright_tech\wechat\response
 */
class CreateQrcodeResponse extends Response
{

    /**
     * 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。
     *
     * @var string
     */
    public $ticket;

    /**
     * 该二维码有效时间，以秒为单位。 最大不超过2592000（即30天）。
     *
     * @var integer
     */
    public $expireSeconds;

    /**
     * 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
     *
     * @var string
     */
    public $url;

    public function afterBuild()
    {
        $this->ticket = $this->convertedResponse['ticket'];
        $this->expireSeconds = $this->convertedResponse['expire_seconds'];
        $this->url = $this->convertedResponse['url'];
    }
}

