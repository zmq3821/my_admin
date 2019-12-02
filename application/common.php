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
 * @param string $msg
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

/*------------------------------------------------ 验证部分 start -----------------------------------------------------*/
/**
 * @notes: 判断字符串是否符合手机号码格式
 * @param $mobile
 * @return bool
 */
function is_mobile($mobile) {
    $regex = "/^(1(([35789][0-9])|(47)))\d{8}$/";
    if (preg_match($regex, $mobile)) {
       return true;
    } else {
        return false;
    }
}

/**
 * @notes: 判断邮箱格式（使用php内置方法验证）
 * @param $email
 * @return bool
 */
function is_email($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

/*------------------------------------------------ 验证部分 end -------------------------------------------------------*/


/**
 * @notes: 生成指定长度的随机字符串
 * @param $length 指定长度
 * @return string
 */
function randomkeys($length){
    $output='';
    for ($a = 0; $a < $length; $a++) {
        $output .= chr(mt_rand(33, 126)); //生成php随机数
    }
    return $output;
}


// php 获取当前访问的完整url
function get_current_url() {
    $url = 'http://';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $url = 'https://';
    }

    // 判断端口
    if($_SERVER['SERVER_PORT'] != '80') {
        $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . ':' . $_SERVER['REQUEST_URI'];
    } else {
        $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['REQUEST_URI'];
    }

    return $url;
}




