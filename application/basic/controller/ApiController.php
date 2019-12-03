<?php
namespace app\basic\controller;

use think\Controller;
use think\Cache;
use think\Db;

class ApiController extends Controller {
	protected $uid;
    /**
     * 构造函数
	 * @author gj
	 * @date 2018-07-30
     */
	public function __construct($parameters = array()) {
		parent::__construct();

	}

    /**
     * @notes: 接口成功时的返回
     * @param string $msg
     * @param array $data
     */
	protected function return_success($msg = '',  $data = array()) {
	    ajax_success($msg, $data);
	}

    /**
     * @notes: 接口失败时的返回
     * @param string $msg
     */
    protected function return_error($msg = '') {
        ajax_error($msg);
    }

    /**
     * Notes:检查验证签名
     * User: feng
     * Date: 2019-07-11
     * Time: 17:56
     * @return bool
     */
	protected function checkSign(){
        //验签
        $posts = $_POST;
        if( !isset($posts['sign']) || empty($posts['sign']) ){
            $posts['sign'] = 'default_sign';
        }
        $post_sign = $posts['sign'];
        unset($posts['sign']);
        $sign  = $this->sign($posts);
        $env = isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'prod';//读取当前环境,默认为最严格的生产
        if($sign != $post_sign && !in_array($env,['dev','feature'])){
            //验签失败
            //不验证 测试环境 和 开发环境
            return false;
        }
        return true;
    }

    /**
     * Notes:app加密验签方法
     * User: feng
     * Date: 2019-05-14
     * Time: 14:54
     * @param $post
     * @return string
     */
	private function sign($post){
        $array = array();

        if(empty($post)) return false;

        // 1. key值做小写处理
	    foreach ($post as $key=>$v){
            $array[strtolower($key)] = $v;
        }
        // 2. 对加密数组进行字典排序 防止因为参数顺序不一致而导致下面拼接加密不同
        ksort($array);

        // 3. 将Key和Value拼接
        $str = "";
        foreach ($array as $k => $v) {
            $str.= strtolower($k).$v;
        }
        $restr=$str. 'ansjy@2019';


        // 4. 通过md5加密并转化为大写
        $sign = md5($restr);
        return $sign;
    }
}
