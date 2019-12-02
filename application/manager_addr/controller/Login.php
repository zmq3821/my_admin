<?php
namespace app\manager_addr\controller;

use app\basic\controller\Admin;
use think\Validate;
use app\manager_addr\model\UserModel;
use app\manager_addr\model\LoginModel;

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
        $params = input('post.');
        //验证数据
        $verify = $this->validate($params,'User.login');
        if (true !== $verify) {
            ajax_error($verify);
        }

        //登陆验证
        $login = new LoginModel();
        $result = $login->checkUserLogin($params);
        $error_msg = $login->getErrorMsg() ?: '用户名或密码错误';
        if (!$result) ajax_error($error_msg);

        $jump_url = cookie('last_act_url') ?: url('Index/index');
        cookie('last_act_url', null);
        ajax_success("验证通过", ['jump_url'=>$jump_url]);
    }

    /**
     * @notes: 注册页面
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:20
     */
    public function register()
    {
        $this->assign('act', request()->action());
        return $this->fetch('login');
    }

    /**
     * @notes: 注册账号
     * @author: zmq
     * @date: 2019/11/29 0029 下午 5:12
     */
    public function doRegister()
    {
        $params = input('post.');
        //验证数据
        $verify = $this->validate($params,'User');
        if (true !== $verify) {
            ajax_error($verify);
        }

        //开始注册
        $login = new LoginModel();
        $result = $login->registerUser($params);
        if (!$result) {
            $err_msg = '注册失败' . ($login->getErrorMsg() ? ',' . $login->getErrorMsg() : '');
            ajax_error($err_msg);
        }

        //注册成功记录状态
        session('mid', $result);

        ajax_success('操作成功',['jump_url'=>url('Index/index')]);
    }

    /**
     * @notes: 退出登陆
     * @author: zmq
     * @date: 2019/11/28 0028 上午 11:21
     */
    public function logout()
    {
        session('mid', null);
        session('uname', null);
        $this->mid = null;
        $this->redirect('manager_addr/Login/login');
    }
}
