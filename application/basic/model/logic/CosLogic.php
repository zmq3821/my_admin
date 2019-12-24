<?php
/**
 * Notes: cos业务模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 下午 3:04
 */

namespace app\basic\model\logic;
use app\basic\model\BaseModel;
use Qcloud\Cos\Client;
use think\Validate;

class CosLogic extends BaseModel
{
    //cos实例
    protected $cosClient;
    //cos密钥信息
    protected $secretId;
    protected $secretKey;
    //设置一个默认的存储桶地域
    protected $region = 'ap-beijing';
    //protected $bucket;
    //cos相关配置信息
    protected $cos_conf = [];

    //自定义初始化
    public function __construct($region='')
    {
        $this->secretId = config('cos_conf.secretId');
        $this->secretKey = config('cos_conf.secretKey');
        $this->cos_conf = config('cos_conf');
        $this->region = $region ?: $this->region;
        $this->cosClient = $this->initCos();
    }

    /**
     * @notes: 初始化cos
     * @return Client
     */
    protected function initCos()
    {
        $client =  array(
            'region' => $this->region,
            'schema' => $this->cos_conf['schema'], //协议头部，默认为http
            'credentials'=> array(
                'secretId'  => $this->secretId,
                'secretKey' => $this->secretKey
            )
        );
        return new Client($client);
    }

    /**
     * @notes: cos上传文件
     * @param $bucket
     * @param $key
     * @param $body
     */
    public function _putObject($bucket, $key, $body)
    {
        if(!$this->checkParams($bucket, $key, $body) ) {
            return $this->returnData(0, '参数错误');
        }
        //putObject(上传接口，最大支持上传5G文件) 66.154% 64.448%
        //bucket 的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
        try {
            $result = $this->cosClient->putObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => fopen($body, 'rb')
            ));
            $this->returnData(1, '上传成功', $result);
        } catch (Qcloud\Cos\Exception\ServiceResponseException $e) {
            //$statusCode = $e->getStatusCode(); // 获取错误码
            $errorMessage = $e->getMessage(); // 获取错误信息
            //$requestId = $e->getRequestId(); // 获取错误的 requestId
            //$errorCode = $e->getCosErrorCode(); // 获取错误名称
            //$request = $e->getRequest(); // 获取完整的请求
            //$response = $e->getResponse(); // 获取完整的响应
            $this->returnData(0, $errorMessage);
        } catch (\Exception $ee) {
            $this->returnData(0, $ee->getMessage());
        }
    }

    public function checkParams($bucket, $key, $body)
    {
        if (empty($bucket) || empty($key) || empty($body)) {
            return false;
        }
        return true;
    }



}
