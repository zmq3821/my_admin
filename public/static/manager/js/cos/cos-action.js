/**
 * 基于腾讯cos cos-js-sdk-v5 的dome做的封装
 * create by zmq 20190802
 * @type {{Bucket: string, Region: string}}
 */

function cos_init(info) {
    var Bucket = info.Bucket;
    var Region = info.Region;
    var StsUrl = info.StsUrl;
    config.Bucket = Bucket;
    config.Region = Region;

    var config = {
        Bucket: Bucket,
        Region: Region,
        StsUrl: StsUrl
    };
    getAuthorization(config,function (res) {
        console.log(res);
    });
    // 初始化实例
    var cos = new COS({
        // 格式一、（推荐）后端通过获取临时密钥给到前端，前端计算签名
        getAuthorization: getAuthorization,
    });


    this.test = function (name) {
        //alert(name);
    };

     function getAuthorization (options, callback) {
         // 服务端 JS 和 PHP 例子：https://github.com/tencentyun/cos-js-sdk-v5/blob/master/server/
         // 服务端其他语言参考 COS STS SDK ：https://github.com/tencentyun/qcloud-cos-sts-sdk
         var sts_url = config.StsUrl;
         $.get(sts_url, {
             bucket: options.Bucket,
             region: options.Region,
         }, function (res) {
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


function putFile(key, file, fn) {
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
        },
        onFileFinish: function (err, data, options) {
            console.log(options.Key + ' 上传' + (err ? '失败' : '完成'));
        },
    }, function(err, data) {
        var return_data;
        if(err){
            return_data = false;
        }else{
            return_data = data;
        }
        if(typeof fn == "function") fn(return_data);
    });
}





