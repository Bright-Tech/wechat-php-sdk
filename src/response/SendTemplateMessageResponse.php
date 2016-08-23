<?php
namespace bright_tech\wechat\response;

use bright_tech\wechat\core\Response;

class SendTemplateMessageResponse extends Response
{

    public $msgid;

    public function afterBuild()
    {
        $this->msgid = $this->convertedResponse['msgid'];

    }
}

