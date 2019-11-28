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
 * @notes: 返回固定结构的json数据
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




