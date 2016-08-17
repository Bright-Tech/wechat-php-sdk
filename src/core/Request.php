<?php
namespace bright_tech\wechat\core;

/**
 *
 * @author SamXiao
 *
 */
class Request
{

    /**
     *
     * @var unknown
     */
    protected $sender;

    /**
     *
     * @return \bright_tech\wechat\core\unknown
     */
    public function getSender()
    {
        if (! $this->sender) {
            $this->sender = new Http();
        }
        return $this->sender;
    }

    /**
     *
     * @param unknown $url
     * @param unknown $params
     */
    public function doGet($url, $params)
    {
        $sender = $this->getSender();
        return $sender->write('GET', $this->generateGetUrl($url, $params));
    }

    /**
     *
     * @param unknown $url
     * @param unknown $params
     * @return string
     */
    protected function generateGetUrl($url, $params)
    {
        return $url . '?' . http_build_query($params);
    }
}

