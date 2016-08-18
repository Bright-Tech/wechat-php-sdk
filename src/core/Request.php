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
     * @var Http
     */
    protected $sender;


    /**
     *
     * @return Http
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
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function doGet($url, $params)
    {
        $sender = $this->getSender();
        return $sender->write('GET', $this->generateGetUrl($url, $params));
    }

    /**
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function generateGetUrl($url, $params)
    {
        return $url . '?' . http_build_query($params);
    }
}

