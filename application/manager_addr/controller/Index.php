<?php
namespace app\manager_addr\controller;

use app\basic\controller\Admin;
use app\basic\model\BaseUserModel;

class Index extends Admin
{
    /**
     * @notes: 后台主页框架
     * @return mixed
     */
    public function index()
    {
        $user = new BaseUserModel();
        $user_info = $user->getUserInfo();
        $this->assign('user_info', $user_info);
        return $this->fetch();
    }

    /**
     * @notes: 后台主页主体页面
     * @return mixed
     */
    public function main()
    {
        return $this->fetch();
    }
}
