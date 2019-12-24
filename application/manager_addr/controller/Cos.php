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
use app\basic\model\logic\CosLogic;
use think\Validate;
use app\basic\model\logic\StsLogic;
use app\basic\model\CosModel;

class Cos extends Administer
{
    public function StsSign()
    {
        $posts = input('request.');
        //数据合法性验证
        $validate = new Validate([
            'bucket_name|bucket参数'=>'require',
        ]);
        if (!$validate->check($posts)) {
            ajax_error($validate->getError());

        }
        $sts_logic = new StsLogic();
        $result = $sts_logic->makeStsSign($posts);
        if(empty($result)){
            ajax_error('生成临时密钥失败');
        }else {
            ajax_success('success',$result);
        }
    }

    /**
     * Notes: 上传文件
     * User: zmq
     * Date: 2019-12-24 21:21
     */
    public function upload()
    {
        $file_type = input('request.file_type/d'); // 0:普通文件，1：图片，2;视频
        $file = request()->file('file');
        if (empty($file)) {
            ajax_error('上传文件无效');
        }
        $file_info = $file->getInfo();
        dump($file_info);die;
        //上传到cos
        $cos_model = new CosModel();
        $result = $cos_model->UploadFile($file_info, $file_type);
        if ($result['status'] !=1) {
            ajax_error('上传文件失败');
        }
        $remote_url = $result['data']['remote_url'];

        //保存文件信息到数据库

    }
}
