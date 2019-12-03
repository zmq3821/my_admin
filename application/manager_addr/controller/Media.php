<?php
/**
 * Notes: 媒体类管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/2 0002
 * Time: 下午 5:18
 */

namespace app\manager_addr\controller;

use app\basic\controller\Administer;

class Media extends Administer
{
    /**
     * @notes: 相册管理
     * @return mixed
     */
    public function photos()
    {
        return $this->fetch();
    }

    public function uploadImg()
    {
        return $this->fetch();
    }

    public function blog_test(){
        dump('asdasdasdasd');die;
    }
}
