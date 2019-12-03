/**
 *cos上传相关方法 封装
 *creat by zmq
 *date 2019/06/12 17:54
 */


//为上传文件重新命名 (规则：前缀加时间)
function change_filename(file_name,prefix) {
    var file_ext = getFileExt(file_name);

    if (!file_ext){
        return file_name;
    }

    return prefix+file_ext;
}

//获取文件后缀名
function getFileExt(file_name) {
    var file_index = file_name.lastIndexOf('.');
    var file_ext = file_name.substring(file_index).toLowerCase();//文件后缀名 例如: ".mp4"

    return file_ext ? file_ext : '';
}

//验证上传文件类型
function checkUploadExt(file,file_type) {

    var file_ext = getFileExt(file.name); //文件格式
    var allow_ext = [];
    if(file_type == 'video'){
        allow_ext =  ['.flv', '.rvmb', '.mp4', '.avi','.wmv'];
    }else if(file_type == 'image' || file_type == 'pic') {
        allow_ext =  ['.gif', '.jpg', '.jpeg', '.png'];
    }
    var res = allow_ext.indexOf(file_ext);
    if(res == -1){
        return false;//上传文件格式验证失败
    }else {
        return true;
    }
}

//小数转百分数
function toPercent(point){
    var str=Number(point*100).toFixed(1);
    str+="%";
    return str;
}

//计算流量速率 默认从'b/s'开始计算
function changeSpeed(speed) {
    if(!speed){
        return '';
    }
    var units = 'b/s';//单位名称
    if(speed/1024>1){
        speed = speed/1024;
        units = 'k/s';
    }
    if(speed/1024>1){
        speed = speed/1024;
        units = 'M/s';
    }
    speed = speed.toFixed(1);

    return (speed+units);
}
//把字节自动换算为KB，M，G
function compute_fiel_size(size,unit='B') {
    unit = unit.toUpperCase();//转化为大写
    var allow_unit = ['B','KB','M','G','T'];
    var index = allow_unit.indexOf(unit);

    if((size/1024)<=1 || index == allow_unit.length-1){
        return size+unit;
    } else{
        return compute_fiel_size((size/1024), allow_unit[index+1]);
    }

}


