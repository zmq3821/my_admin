<?php
/**
 * Notes: 腾讯cos控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/4 0004
 * Time: 下午 1:38
 */

namespace app\manager_addr\controller;
use app\basic\controller\Administer;
use think\Validate;

class Cos extends Administer
{
    public function StsSign()
    {
        $posts = input('request.');
        //数据合法性验证
        $validate = new Validate([
            'bucket_name|bucket参数'=>'require',
        ]);
        $re = $validate->batch()->check($posts);
        if(!$re){
            $error_tips = current($validate->getError()) ;
            ajax_error($error_tips) ;
        }
        $bucket = $posts['bucket_name'];
        $cos_config = config('cos_config');
        if (!isset($cos_config['bucket_info'][$bucket])) {
            ajax_error("存储桶名无效") ;
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

        if(empty($tempKeys)){
            ajax_error('生成临时密钥失败');
        }else {
            ajax_success('success',$tempKeys);
        }
    }
}
