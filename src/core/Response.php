<?php
namespace bright_tech\wechat\core;


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
    protected $response;

    /**
     *
     * @var array
     */
    protected $convertedResponse;

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
        if (isset($jsonArr['errcode']) && $jsonArr['errcode'] > 0) {
            throw new \Exception('Error(#' . $jsonArr['errcode'] . '): ' . $jsonArr['errmsg']);
        }
        return true;
    }
}

