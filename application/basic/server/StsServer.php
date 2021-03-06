<?php
/**
 * Notes: 腾讯云COS临时密钥服务搭建
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/3 0003
 * Time: 下午 4:32
 */

namespace app\basic\server;

use think\Model;
use sts;

class StsServer extends Model
{

    /**
     * 生成cos密钥
     * @param array $cos_arr 配置信息
     * @return array|bool|mixed|string
     */
    public function createCosSign(array $cos_arr){
        if (empty($cos_arr)) return array();
        $sts = new STS();
        // 配置参数
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => $cos_arr['proxy'],
            'secretId' => $cos_arr['secretId'], // 固定密钥
            'secretKey' => $cos_arr['secretKey'], // 固定密钥
            'bucket' => $cos_arr['bucket'], // 换成的 bucket
            'region' => $cos_arr['region'], // 换成 bucket 所在园区
            'durationSeconds' => $cos_arr['durationSeconds'], // 密钥有效期
            'allowPrefix' => $cos_arr['allowPrefix'], // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的目录，例子：* 或者 a/* 或者 a.jpg
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 所有 action 请看文档 https://cloud.tencent.com/document/product/436/31923
                // 简单上传
                'name/cos:PutObject',
                'name/cos:PostObject',
                // 分片上传
                'name/cos:InitiateMultipartUpload',
                'name/cos:ListMultipartUploads',
                'name/cos:ListParts',
                'name/cos:UploadPart',
                'name/cos:CompleteMultipartUpload',
                //下载操作
                "name/cos:GetObject",
                //查询对象元数据
                "name/cos:HeadObject"
            )
        );
        // 获取临时密钥，计算签名
        $tempKeys = $sts->getTempKeys($config);
        // 返回数据给前端
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: http://127.0.0.1'); // 这里修改允许跨域访问的网站
        header('Access-Control-Allow-Headers: origin,accept,content-type');
        return empty($tempKeys) ? array() : $tempKeys;
    }


}
