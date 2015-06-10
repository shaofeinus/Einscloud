/**
 * Created by Shao Fei on 10/6/2015.
 */

function call_php_func(url,func, params) {
    var data = {
        func: func, params: params
    }
    $.post(url, data,
    function(data, status) {
        console.log(status + ": " + data);
    })
}