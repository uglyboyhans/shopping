//检查登录状态，确定top和left的内容
$.ajax({
    type: 'GET',
    crossDomain: true,
    url: serverUrl() + "/index.php?m=Account&f=isLogin",
    dataType: 'json',
    success: function (result) {
        if (result.result == 0) {//会员已登录
            $.ajax({//插入top
                url: "top_member.html",
                cache: false,
                success: function (html) {
                    $("#top").replaceWith(html);
                }
            });
            $.ajax({//插入left
                url: "left_member.html",
                cache: false,
                success: function (html) {
                    $("#left").replaceWith(html);
                }
            });
        }
        if (result.result == 1) {//未登录
            $.ajax({//插入top
                url: "top_tourist.html",
                cache: false,
                success: function (html) {
                    $("#top").replaceWith(html);
                }
            });
            $.ajax({//插入left
                url: "left_tourist.html",
                cache: false,
                success: function (html) {
                    $("#left").replaceWith(html);
                }
            });
        }
        if (result.result == 2) {//管理员已登录
            $.ajax({//插入top
                url: "top_admin.html",
                cache: false,
                success: function (html) {
                    $("#top").replaceWith(html);
                }
            });
            $.ajax({//插入left
                url: "left_admin.html",
                cache: false,
                success: function (html) {
                    $("#left").replaceWith(html);
                }
            });
        }
    },
    error: function () {
        alert('网络错误');
    }
});

//检查登录状态并返回结果代码
function checkLogin() {
    var result;
    $.ajax({
        type: 'GET',
        crossDomain: true,
        url: serverUrl() + "/index.php?m=Account&f=isLogin",
        dataType: 'json',
        async: false,//关闭异步！！
        success: function (data) {
            result = data.result;
        },
        error: function () {
            alert('网络错误');
        }
    });
    return result;
}