<?php
namespace app\manager_addr\controller;

use app\basic\controller\Admin;
use Captcha;

class Login extends Admin
{
    public function Login()
    {
        return $this->fetch();
    }

    public function doLogin()
    {
        session('mid', 34);
        $jump_url = redirect()->restore();
        dump($jump_url);die;
    }

    public function getCaptcha()
    {
        $captcha = new Captcha();
        return $captcha->entry();
    }
}
