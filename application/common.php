<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @notes: ajax成功的返回
 * @param int $status 状态码
 * @param string $msg
 * @param array $data
 * @author: zmq
 * @date: 2019/11/28 0028 下午 2:30
 */
function ajax_success($msg='', array $data=array()){
    header('Content-Type:application/json');
    $msg = empty($msg) ? "操作成功" : $msg;
    $result = array('status'=>1, 'msg'=>$msg, 'data'=>$data);

    echo json_encode($result);
    exit;
}

/**
 * @notes: ajax失败的返回
 * @param string $msg
 * @author: zmq
 * @date: 2019/11/29 0029 上午 9:03
 */
function ajax_error($msg='') {
    header('Content-Type:application/json');
    $result = array('status'=>0, 'msg'=>'操作失败');
    if(is_array($msg)){
        $result = array_merge($result, $msg);
    }elseif(!empty($msg)) {
        $result['msg'] = $msg;
    }
    echo json_encode($result);
    exit;
}

/**
 * @notes: 判断字符串是否符合手机号码格式
 * @param $mobile
 */
function is_mobile($mobile) {
    $regex = "/^(1(([35789][0-9])|(47)))\d{8}$/";
    if (preg_match($regex, $mobile)) {
       return true;
    } else {
        return false;
    }
}




