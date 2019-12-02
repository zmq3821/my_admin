<?php
namespace app\manager_addr\model;

use app\basic\model\BaseModel;
use app\basic\model\BaseUser as basicUser;
use app\basic\model\BaseUserModel;
use app\manager_addr\validate\User;

class LoginModel extends BaseModel
{
    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
        $this->error_msg = '';
    }

    /**
     * @notes: 验证登陆
     * @author: zmq
     * @date: 2019/12/2 0002 下午 2:04
     */
    public function checkUserLogin(array $params)
    {
        if (empty($params)) return false;
        $uname = trim($params['user_name']);
        $password = trim($params['password']);

        $where = ['is_del'=>BaseUserModel::NO];
        //判断账号类型（用户名/电话/邮箱）
        if (is_mobile($uname)) {
            $where['phone'] = $uname;
        }elseif (is_email($uname)) {
            $where['email'] = $uname;
        } else {
            $where['user_name'] = $uname;
        }

        $user = new UserModel();
        $user_data = $user->where($where)->field('uid,user_name,phone,email,password')->find();
        if (empty($user_data)) {
            $this->setErrorMsg("用户名不存在");
            return false;
        }
        if (false === password_verify($password, $user_data['password'])) {
            $this->setErrorMsg("用户名或密码错误");
            return false;
        }
        //记录登录状态
        session('mid', $user_data['uid']);
        session('uname', $user_data['user_name']);
        return true;
    }



    /**
     * @notes: 用户注册
     * @author: zmq
     * @date: 2019/11/29 0029 下午 5:33
     */
    public function registerUser(array $params)
    {
        if (empty($params)) return false;
        $data = [
            'user_name'         =>  trim($params['user_name']),
            'phone'             =>  $params['phone'],
            'email'             =>  $params['email'],
            'password'          =>  self::passwordToHash($params['password']),
            'last_login_time'   =>  time()//记录登录时间
        ];

        //检测是否存在重复用户
        $user = new UserModel();
        $has_user = $user->field('uid,phone,email')->where('user_name',$data['user_name'])->whereOr('phone',$data['phone'])->whereOr('email',$data['email'])->find();
        if (!empty($has_user)) {
            if ($has_user['phone'] == $data['phone']) {
                $this->setErrorMsg('该用户名已存在');
                return false;
            }
            if ($has_user['phone'] == $data['phone']) {
                $this->setErrorMsg('该手机号已存在');
                return false;
            }
            if ($has_user['email'] == $data['email']) {
                $this->setErrorMsg('该邮箱已存在');
                return false;
            }
        }
        //$insert_id = $user->insertGetId($data);
        $insert_id = $user->data($data)->save();
        if ($insert_id) {
            return (int) $insert_id;
        } else {
            $this->setErrorMsg('用户数据写入失败');
            return false;
        }
    }

    /**
     * @notes: 生成密码
     * @param $password
     * @return bool|string
     */
    protected function passwordToHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @notes: 密码验证
     * @param $password 密码
     * @param $salt 盐值
     * @return bool
     */
    protected function checkPassword($password, $salt)
    {
        if (empty($password)) return false;

    }

}
