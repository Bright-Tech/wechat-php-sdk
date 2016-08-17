<?php
namespace bright_tech\wechat\core;

class Request
{

    protected $sender;

    public function getSender()
    {
        if (! $this->sender) {
            $this->sender = new Http();
        }
        return $this->sender;
    }

    public function doGet($url, $params)
    {
        $sender = $this->getSender();
        return $sender->write('GET', $this->generateGetUrl($url, $params));
    }

    protected function generateGetUrl($url, $params)
    {
        return $url . '?' . http_build_query($params);
    }
}

