<?php
namespace app\basic\model;

use think\Model;

class User extends Model
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //自定义的初始化
    }

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    //自动写入创建和更新的时间戳字段
    protected $autoWriteTimestamp = true;

    // 表操作允许的字段
    protected $allow_fields = [
        'uid','user_name','password','login_salt','email','sex','phone','intro','last_login_time','is_del'
    ];

    const YES = 1;
    const NO = 0;






}
