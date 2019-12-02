<?php
namespace app\basic\model;

use think\Model;

class BaseUserModel extends Model
{
    // 设置当前模型对应的数据表名称（不加前缀）
    protected $table_name = 'user';
    protected $table = 'ts_user';
    protected $pk = 'uid';

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

    //通用的状态码
    const YES = 1;
    const NO = 0;


    public function getUserInfo($uid=0)
    {
        $uid = $uid ?: intval(session('mid'));
        if (empty($uid)) return [];

        $field = "uid,user_name,phone,email,last_login_time,sex,intro,create_time";
        $data = $this->where(['is_del'=>self::NO, 'uid'=>$uid])->field($field)->find();

        return empty($data) ? [] : $data->getData();
    }






}
