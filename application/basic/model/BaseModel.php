<?php
/**
 * Notes: 通用的model基类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/2 0002
 * Time: 下午 1:42
 */

namespace app\basic\model;

use think\Model;

class BaseModel extends Model
{

    //错误信息
    public $error_msg = null;


    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
        $this->error_msg = '';
    }

    /**
     * @notes: 设置错误信息
     * @param string $msg
     * @author: zmq
     */
    public function setErrorMsg($msg='')
    {
        $this->error_msg = $msg;
    }

    /**
     * @notes: 获取错误信息
     * @return string
     * @author: zmq
     */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }
}
