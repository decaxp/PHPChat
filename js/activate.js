/**
 * Created by Dmitry on 27.04.2017.
 */
// Проверка активна ли в настоящий момент вкладка браузера
var isActive = true;

function ajaxActivate(type){
    var url='delActiveUser.php';
    if (type=='add'){
        url='addActiveUser.php';
    }
    url+="?id=";

    //распарсить строку запроса
    var params = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    // console.log( params['id']);
    url+=params['id'];
    // console.log(url);

    $.ajax({
        type: "GET",
        url: url,
//            contentType: "application/json; charset=utf-8",
//            dataType: "json",
        success: function(data){
           console.log(data);
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

function onBlur() { // окно теряет фокус
    isActive = false;
    ajaxActivate('del');
}
function onFocus() {// окно получает фокус
    isActive = true;
    ajaxActivate('add');
// что-то делаем
}
if (/*@cc_on!@*/false) { // для Internet Explorer
    document.onfocusin = onFocus;
    document.onfocusout = onBlur;
} else {
    window.onfocus = onFocus;
    window.onblur = onBlur;
}

