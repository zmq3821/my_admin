<?php
/**
 * Notes: cos临时密钥模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 下午 3:30
 */

namespace app\basic\model\logic;


use app\basic\model\BaseModel;

class StsLogic extends BaseModel
{

    /**
     * @notes: 获取sts临时密钥
     * @param $posts
     * @return array|bool|mixed|string
     */
    public function makeStsSign($bucket)
    {
        $cos_config = config('cos_config');
        if (!isset($cos_config['bucket_info'][$bucket])) {
            $this->setErrorMsg('bucket参数无效');
            return false;
        }
        $region = empty($cos_config['bucket_info'][$bucket]['region']) ?: $cos_config['region'];

        //配置必须参数
        $cos_arr = array(
            'proxy'           =>'',
            'secretId'        => $cos_config['secretId'], // 固定密钥
            'secretKey'       => $cos_config['secretKey'], // 固定密钥
            'bucket'          => $bucket, // 换成的 bucket
            'region'          => $region, // 换成 bucket 所在园区
            'durationSeconds' => 1800, // 密钥有效期
            'allowPrefix'     => '*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的目录，例子：* 或者 a/* 或者 a.jpg
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
        );

        //生成临时密钥
        $sts = new \app\basic\server\StsServer();
        $tempKeys = $sts->createCosSign($cos_arr);
        return $tempKeys;
    }
}
