function serverUrl() {
    return 'http://www.cqushop.cn';
}

function getUrlParam(name) {
    //构造一个含有目标参数的正则表达式对象  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    //匹配目标参数  
    var r = window.location.search.substr(1).match(reg);
    //返回参数值  
    if (r !== null) {
        return decodeURIComponent(r[2]);
    }
    return null;
}