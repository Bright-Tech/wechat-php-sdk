<?php
namespace bright_tech\wechat\core;

use function GuzzleHttp\json_encode;

/**
 *
 * @author SamXiao
 *
 */
abstract class Response
{

    /**
     *
     * @var string
     */
    public $response;

    /**
     *
     * @var array
     */
    public $convertedResponse;

    /**
     *
     * @param string $jsonString
     */
    public function __construct($jsonString)
    {
        $this->response = $jsonString;
        $this->convertedResponse = json_decode($jsonString, true);
        $this->checkError();
        $this->afterBuild();
    }

    /**
     */
    public abstract function afterBuild();

    /**
     *
     * @throws \Exception
     * @return boolean
     */
    public function checkError()
    {
        $jsonArr = $this->convertedResponse;
        if (isset($jsonArr['errcode'])) {
            throw new \Exception('Error(#' . $jsonArr['errcode'] . '): ' . $jsonArr['errmsg']);
        } else {
            throw new \Exception('出错了:' . json_encode($jsonArr));
        }
        return true;
    }
}

