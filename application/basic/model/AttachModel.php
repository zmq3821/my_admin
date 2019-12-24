<?php
/**
 * Notes: 附件类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 下午 5:16
 */

namespace app\basic\model;
use app\basic\model\BaseModel;

class AttachModel extends BaseModel
{

    // 设置当前模型对应的完整数据表名称
    protected $table = 'ts_attach';

    // 设置主键
    protected $pk = 'uid';


    public function formatAttachData($file_info, $file_type=0)
    {
        if (!$file_info) return false;
        $data = [];
        if ($file_type == 'image') { // 图片
            $image_info = getimagesize($file_info['tmp_name']);
            if (empty($image_info)) return false;

        } elseif ($file_type == 'video') { // 视频

        } else { //普通文件

        }

    }
}
