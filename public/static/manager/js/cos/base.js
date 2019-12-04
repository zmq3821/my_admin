
/**
 * 腾讯cos cos-js-sdk-v5 的基础dome
 * create by zmq 20190802
 * @type {{Bucket: string, Region: string}}
 */
var config = {
    Bucket: 'wwxd-image-1258943427',
    Region:'ap-beijing',
    StsUrl: ''
};
var util = {
    createFile: function (options) {
        var buffer = new ArrayBuffer(options.size || 0);
        var arr = new Uint8Array(buffer);
        [].forEach.call(arr, function (char, i) {
            arr[i] = 0;
        });
        var opt = {};
        options.type && (opt.type = options.type);
        var blob = new Blob([buffer], options);
        return blob;
    }
};

// 对更多字符编码的 url encode 格式
var camSafeUrlEncode = function (str) {
    return encodeURIComponent(str)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A');
};

// 格式一、（推荐）后端通过获取临时密钥给到前端，前端计算签名
var getAuthorization = function (options, callback) {
    // 服务端 JS 和 PHP 例子：https://github.com/tencentyun/cos-js-sdk-v5/blob/master/server/
    // 服务端其他语言参考 COS STS SDK ：https://github.com/tencentyun/qcloud-cos-sts-sdk
    var sts_url = config.StsUrl;
    $.get(sts_url, {
        bucket: options.Bucket,
        region: options.Region,
    }, function (data) {
        if (data.status == 1) {
            var data = (new Function("return " + data))();
            //console.log(data);
            callback({
                TmpSecretId: data.credentials.tmpSecretId,
                TmpSecretKey: data.credentials.tmpSecretKey,
                XCosSecurityToken: data.credentials.sessionToken,
                ExpiredTime: data.expiredTime
            });

        } else {

        }

    });
};

//实例化cos
var cos = new COS({
    getAuthorization: getAuthorization,
});

var TaskId;

var pre = document.querySelector('.result');
var showLogText = function (text, color) {
    if (typeof text === 'object') {
        try {
            text = JSON.stringify(text);
        } catch (e) {
        }
    }
    var div = document.createElement('div');
    div.innerText = text;
    color && (div.style.color = color);
    pre.appendChild(div);
    pre.style.display = 'block';
    pre.scrollTop = pre.scrollHeight;
};

var logger = {
    log: function (text) {
        console.log.apply(console, arguments);
        var args = [].map.call(arguments, function (v) {
            return typeof v === 'object' ? JSON.stringify(v) : v;
        });

        var logStr = args.join(' ');

        if(logStr.length > 1000000) {
            logStr = logStr.slice(0, 1000000) + '...content is too long, the first 1000000 characters are intercepted';
        }

        showLogText(logStr);
    },
    error: function (text) {
        console.error(text);
        showLogText(text, 'red');
    },
};

/*--------------------------------------------------------------------------------------------------------------------*/
/**
 * 查询对象的元数据信息
 * @param key
 */
function headObject(key) {
    cos.headObject({
        Bucket: config.Bucket,
        Region: config.Region,
        Key: key
    }, function (err, data) {
        logger.log(err || data);
    });
}

/**
 * 获取存储桶里指定对象的内容，得到内容是字符串格式
 * @param key
 */
function getObject(key='') {
    cos.getObject({
        Bucket: config.Bucket, // Bucket 格式：test-1250000000
        Region: config.Region,
        Key: key,
    }, function (err, data) {
        logger.log(err || data);
    });
}

/**
 * 获取文件 Url 并下载文件。
 * @param key 存储桶中的文件key
 * @param expires 指定链接有效时间（秒）
 */
function getObjectUrl(key='',expires=60) {
    cos.getObjectUrl({
        Bucket:  config.Bucket,
        Region: config.Region,
        Key: key,
        Sign: true
    }, function (err, data) {
        if (err) return console.log(err);
        var downloadUrl = data.Url + (data.Url.indexOf('?') > -1 ? '&' : '?') + 'response-content-disposition=attachment'; // 补充强制下载的参数
        downFileByIframe(downloadUrl);
        //window.open(downloadUrl); // 这里是新窗口打开 url，如果需要在当前窗口打开，可以使用隐藏的 iframe 下载，或使用 a 标签 download 属性协助下载
    });
}

/**
 *  文件简单上传
 * @param key
 * @param file
 * @param fn
 * @returns {boolean}
 */
function uploadPut(key,file,fn) {
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


/**
 * 分块上传文件
 * @param key 文件名
 * @param file 文件信息
 * @param fn 回调方法
 * @returns {boolean}
 */
function uploadObjectSlice(key,file,fn) {
    if (!key || !file) return false;
    cos.sliceUploadFile({
        Bucket:  config.Bucket,
        Region: config.Region,
        Key: key,              /* 必须 */
        Body: file,                /* 必须 */
        onProgress: function (info) {
            var percent = parseInt(info.percent * 10000) / 100;
            var speed = parseInt(info.speed / 1024 / 1024 * 100) / 100;
            console.log('进度：' + percent + '%; 速度：' + speed + 'Mb/s;');
        },
        onFileFinish: function (err, data, options) {
            console.log(options.Key + ' 上传' + (err ? '失败' : '完成'));
        },
    }, function(err, data) {
        //console.log(err || data);
        var return_data;
        if(err){
            return_data = false;
        }else{
            return_data = data;
        }
        if(typeof fn == "function") fn(return_data);
    });
}



/**
 * 上传文件，支持分块上传
 * @param filename
 * @returns {boolean}
 */
function uploadFiles(filename) {
    //var blob = util.createFile({size: 1024 * 1024 * 10});
    console.log(blob);return false;
    cos.uploadFiles({
        files: [{
            Bucket: config.Bucket, // Bucket 格式：test-1250000000
            Region: config.Region,
            Key: '1' + filename,
            Body: blob,
        }, {
            Bucket: config.Bucket, // Bucket 格式：test-1250000000
            Region: config.Region,
            Key: '2' + filename,
            Body: blob,
        }, {
            Bucket: config.Bucket, // Bucket 格式：test-1250000000
            Region: config.Region,
            Key: '3' + filename,
            Body: blob,
        }],
        SliceSize: 1024 * 1024,//文件大小多大以上使用分片上传，单位 B，小于等于改数值会使用 putObject 上传，大于该数值会使用 sliceUploadFile 上传
        onProgress: function (info) {
            var percent = parseInt(info.percent * 10000) / 100;
            var speed = parseInt(info.speed / 1024 / 1024 * 100) / 100;
            console.log('进度：' + percent + '%; 速度：' + speed + 'Mb/s;');
        },
        onFileFinish: function (err, data, options) {
            console.log(options.Key + ' 上传' + (err ? '失败' : '完成'));
        },
    }, function (err, data) {
        console.log(err || data);
    });
}







/**
 * 使用隐藏的 iframe 下载文件
 * @param downloadURL
 */
function downFileByIframe(downloadURL) {
    var iframe = document.createElement("iframe");
    iframe.src = downloadURL;
    iframe.style.display = "none";
    document.body.appendChild(iframe);
}

/**
 * 截取掉cos的访问域名，返回key值
 * @param path
 * @param bucket
 * @param region
 */
function cutCosUrl(path='', bucket='',region='') {
    if(path.length == '') return path;

    bucket  = bucket || config.Bucket;
    region  = region || config.Region;

    var cos_url_1 = "https://"+bucket+".cos."+region+".myqcloud.com"; //例如：https://wwxd-media-1258943427.cos.ap-beijing.myqcloud.com
    var cos_url_2 = "http://"+bucket+".cos."+region+".myqcloud.com";
    var str = path.replace(cos_url_1,'');
    var newptah = str.replace(cos_url_2,'');

    return newptah;
}

