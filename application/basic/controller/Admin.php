<?php
namespace app\basic\controller;

use think\Controller;
class Admin extends Controller
{
    protected $mid;

    public function _initialize()
    {
        parent::_initialize();
        $mid = session('mid');
        //检测登陆
        if ($mid) {
            // todo 查询用户信息状态
            $this->mid = $mid;
        } else {
            $mod = request()->controller();
            if($mod != 'Login') {
                //记住本次请求地址
                redirect('manager_addr/Login/login')->remember();
                $this->redirect('manager_addr/Login/login');
            }
        }

    }
}
