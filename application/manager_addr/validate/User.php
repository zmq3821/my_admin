<?php
/**
 * Notes: 用户数据验证类模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29 0029
 * Time: 上午 9:38
 */

namespace app\manager_addr\validate;

use think\Validate;

class User extends Validate
{
    //验证器规则
    protected $rule = [
        'verify'        =>  'require|captcha',
        'user_name'     =>  'require|min:4',
        'phone'         =>  'checkPhone',
        'email'         =>  'email',
        'password'      =>  'require|min:6',
        'repassword'    =>  'require|confirm:password',
    ];

    //验证器提示信息
    protected $message = [
        'verify'        =>  '验证码错误',
        'user_name'     =>  '用户名/账号不能为空',
        'user_name.min' =>  '用户名/账号最少4位',
        'phone'         =>  '用手机号格式错误',
        'email'         =>  '邮箱格式错误',
        'password'      =>  '密码不能为空',
        'password.min'  =>  '密码最少6位',
        'repassword'    =>  '两次密码不一致'
    ];

    //验证器场景
    protected $scene = [
        'edit'   =>  ['user_name', 'phone', 'email'],
        'login'  =>  ['verify', 'password', 'user_name'=>'require|min:4'],
    ];


    /*------------------------------------自定义的字段验证------------------------------------*/
    /**
     * @notes: 验证手机号的自定义规则
     * @param $value 验证数据
     * @param $rule 验证规则
     * @param $data 全部数据（数组）
     * @param $field 字段名
     * @return bool|string
     * @author: zmq
     * @date: 2019/11/29 0029 上午 10:10
     */
    protected function checkPhone($value, $rule, $data, $field) {
        if (is_mobile($value)) {
            return true;
        } else {
            return $field . '格式错误';
        }
    }

}
