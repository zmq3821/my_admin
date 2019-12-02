<?php
namespace app\manager_addr\model;

use think\Db;
use think\Model;
use app\basic\model\BaseUserModel;

class UserModel extends BaseUserModel
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //自定义的初始化
    }



}
