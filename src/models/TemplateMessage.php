<?php
namespace bright_tech\wechat\models;

/**
 * Created by PhpStorm.
 * User: SamXiao
 * Date: 16/8/22
 * Time: 上午9:59
 */
class TemplateMessage extends Model
{
    /**
     * 接收者openid
     * 必填
     * @var string
     */
    public $touser;

    /**
     * 模板ID
     * 必填
     * @var string
     */
    public $templateId;

    /**
     * 模板跳转链接
     * @var string
     */
    public $url = '';

    /**
     * 模板数据
     * 必填
     * @var array
     */
    public $data;


    public function toJson()
    {
        $arr = [
            'touser' => $this->touser,
            'template_id' => $this->templateId,
            'url' => $this->url,
            'data' => $this->data
        ];
        return json_encode($arr);
    }
}