<?php
/**
 * Notes: cos文件模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 下午 12:30
 */

namespace app\basic\model;

use app\basic\model\BaseModel;
use Qcloud\Cos\Client;
use app\basic\model\logic\CosLogic;

class CosModel extends BaseModel
{
    // 文件类型 0：普通文件，1：图片，2：视频
    const FILE_TYPE_0 = 0;
    const FILE_TYPE_1 = 1;
    const FILE_TYPE_2 = 2;

    /**
     * @notes: 上传文件
     * @param $file_info 文件信息
     * @param $file_type 文件类型 0:普通文件，1：图片，2;视频
     */
    public function uploadFile($file_info, $file_type=0)
    {
        if (!$file_info) return false;

        $cos_logic = new CosLogic();
        $bucket = $this->getBucket($file_type);
        if (empty($bucket)) {
            return $this->returnData(0, "储存桶无效");
        }
        $key = $this->resetFileName($file_info['name']);

        $result = $cos_logic->_putObject($bucket, $key, $file_info['tmp_name']);
        if ($result['status'] != 1) {
            return $this->returnData(0, $result['msg']);
        }
        $remote_url = $result['data']['Location'];

        //保存文件信息
        $data = [
            'attach_type'   => $file_type,
            'create_time'   => time(),
            'name'          => $key,
            'type'          => $file_info['type'],
            'size'          => $file_info['size'],
        ];
        $attach_model = new AttachModel();
        $attach_model->data($data);
        return $attach_model->save();
    }

    /**
     * @notes: 根据上传类型选择bucket
     * @param $file_type
     * @return string
     */
    public function getBucket($file_type)
    {
        $bucket= '';
        switch ($file_type)
        {
            case self::FILE_TYPE_1:
                $bucket = "common-img-1255561412";
                break;
            case self::FILE_TYPE_2:
                //$bucket = "common-img-1255561412";
                break;
            default:
                $bucket = "common-img-1255561412";
        }
        return $bucket;
    }

    /**
     * @notes: 为上传文件重新命名
     * @param $file_type
     * @return string
     */
    public function resetFileName($filename)
    {
        //todo 重命名逻辑
        return $filename;
    }

}
