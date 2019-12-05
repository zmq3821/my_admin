/**
 * 基于腾讯cos cos-js-sdk-v5 的dome做的封装
 * create by zmq 20190802
 * @type {{Bucket: string, Region: string}}
 */

function cos_init(info) {
    var Bucket = info.Bucket;
    var Region = info.Region;

    var config = {
        Bucket: Bucket,
        Region: Region,
        StsUrl: info.StsUrl
    };

    try {
        // 初始化实例
        var cos = new COS({
            getAuthorization: getAuthorization,// 格式一、（推荐）后端通过获取临时密钥给到前端，前端计算签名
            ProgressInterval: '500',
        });

        /**
         * 文件上传
         * @param key
         * @param file
         * @param fn
         * @returns {boolean}
         */
        this.uploadFile = function(key,file,fn) {
            if (!key || !file) return false;
            cos.putObject({
                Bucket:  config.Bucket,
                Region: config.Region,
                Key: key,              /* 必须 */
                Body: file,                /* 必须 */
                StorageClass: 'STANDARD',
                onProgress: function (info) {
                    var percent = parseInt(info.percent * 10000) / 100;
                    var speed = parseInt(info.speed / 1024 / 1024 * 100) / 100;
                    console.log('进度：' + percent + '%; 速度：' + speed + 'Mb/s;');
                    if(typeof fn == "function") fn('progress', 1, info);
                }
            }, function(err, data) {
                if(err){
                    if(typeof fn == "function") fn('done', 0, err);
                }else{
                    if(typeof fn == "function") fn('done',  1, data);
                }
            });
        }
    } catch (e) {
        console.log(e);
    }

    function getAuthorization (options, callback) {
        // 服务端 JS 和 PHP 例子：https://github.com/tencentyun/cos-js-sdk-v5/blob/master/server/
        // 服务端其他语言参考 COS STS SDK ：https://github.com/tencentyun/qcloud-cos-sts-sdk
        var sts_url = config.StsUrl;
        $.get(sts_url, {
            bucket: options.Bucket,
            region: options.Region,
        }, function (res) {
            if (res.status != 1) throw "获取临时密钥失败";

            var credentials = res.data.credentials;
            callback({
                TmpSecretId: credentials.tmpSecretId,
                TmpSecretKey: credentials.tmpSecretKey,
                XCosSecurityToken: credentials.sessionToken,
                ExpiredTime: res.data.expiredTime
            });
        });
    }


}






