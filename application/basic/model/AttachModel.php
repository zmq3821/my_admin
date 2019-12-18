<?php
/**
 * Notes: 附件类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 下午 5:16
 */

namespace app\basic\model;
use app\basic\model\BaseModel;

class AttachModel extends BaseModel
{

    // 设置当前模型对应的完整数据表名称
    protected $table = 'ts_attach';

    // 设置主键
    protected $pk = 'uid';


}
