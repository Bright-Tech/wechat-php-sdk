<?php
namespace bright_tech\wechat\models;

/**
 * 生成带参数的二维码
 *
 * Created by PhpStorm.
 * User: SamXiao
 * Date: 16/8/22
 * Time: 上午9:59
 */
class Qrcode extends Model
{
    /**
     * 该二维码有效时间，以秒为单位。 最大不超过2592000（即30天），此字段如果不填，则默认有效期为30秒;
     *
     * 永久二维码可不填
     * @var integer
     */
    public $expireSeconds = '';

    /**
     * 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值
     *
     * @var string
     */
    public $actionName;

    /**
     * 二维码详细信息
     *  [
     *      'scene' =>
     *      [
     *          'scene_id' => 123, 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
     *          'scene_str' => ''  场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
     *      ]
     *  ]
     *
     * @var array
     */
    public $actionInfo;


    public function toJson()
    {

        $arr = [
            'expire_seconds' => $this->expireSeconds,
            'action_name' => $this->actionName,
            'action_info' => $this->actionInfo,
        ];
        if (!empty($this->expireSeconds)) {
            $arr['expire_seconds'] = $this->expireSeconds;
        }
        return json_encode($arr);
    }
}