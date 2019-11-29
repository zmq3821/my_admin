<?php
namespace app\manager_addr\controller;

use app\basic\controller\Admin;
use think\Validate;
use app\basic\model\User;

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
        $verify = $this->validate($params,'User');
        if (true !== $verify) {
            ajax_error($verify);
        }

        //写入数据
        $user = new User($params);
        $insert_id = $user->allowField(true)->save();
        if ($insert_id) {
            $user_info = $user->where(['uid'=>$insert_id,'is_del'=>User::NO])->find();
            if(!empty($user_info)) {
                $this->mid = $insert_id;
                session('mid', $insert_id);
                session('user_name', $user_info['user_name']);
            }
        } else {
            ajax_error("注册失败");
        }

        ajax_success('操作成功');
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
