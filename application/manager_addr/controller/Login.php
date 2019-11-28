<?php
namespace app\manager_addr\controller;

use app\basic\controller\Admin;
use \think\captcha\Captcha;
use think\Config;
use think\Validate;

class Login extends Admin
{
    /**
     * @notes: 登录
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:20
     */
    public function Login()
    {
        $this->assign('act', request()->action());
        return $this->fetch();
    }

    /**
     * @notes: 登录验证
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:21
     */
    public function doLogin()
    {
        //session('mid', 34);
        $jump_url = redirect()->restore();
        dump($jump_url);die;
    }

    /**
     * @notes: 注册
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:20
     */
    public function register()
    {
        $this->assign('act', request()->action());
        return $this->fetch('login');
    }

    public function doRegister()
    {
        $params = input('post.');
        $validate = new Validate([
            'user_name'  => 'require',
            'phone'      => 'phone',
            'email'      => 'email'
        ]);
        if (!$validate->check($params)) {
            dump($validate->getError());
            ajax_error($validate->getError());
        }
        $result = array();
        ajax_success('操作成功',$result);
        dump($params);die;
    }

    /**
     * @notes: 退出登陆
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:21
     */
    public function logout()
    {

    }
}
