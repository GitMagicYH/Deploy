var ajax_lock = 0;
var _ajax_submit = function(url, param, successProccess) {
    if (ajax_lock == 1) {
        alert("正在进行异步提交，请不要频繁提交");
    };
    ajax_lock = 1;
    $.ajax({
        url : url,
        type : 'post',
        data : param,
        async : true,
        dataType : 'json',
        success : function(data) {
            ajax_lock = 0;
            if (data['code'] != '0') {
                alert(data['msg']);
            }
            else {
                successProccess(data);
            }
            return false;
        },
        error: function(xhr) {
            ajax_lock = 0;
            var content = xhr.responseText;
            var OpenWindow = window.open("");
            OpenWindow.document.write(content);
            return false;
        }
    });
    return false;
};