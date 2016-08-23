<?php
namespace bright_tech\wechat\core;

use bright_tech\wechat\models\Model;

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
        if (!$this->sender) {
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
        $url = $this->generateGetUrl($url, $params);
        return $sender->write('GET', $url);
    }

    /**
     *
     * @param string $url
     * @param Model $model
     *
     * @return string
     */
    public function doPost($url, Model $model)
    {
        $sender = $this->getSender();
        return $sender->write('POST', $url, ['Content-Type: text/plain'], $model->toJson());
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

