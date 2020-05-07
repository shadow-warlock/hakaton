const LOG_IN = "login";

function authSubmit() {
    let login = $('#auth-login-input').val();
    let password = $('#auth-pass-input').val();
    let params = {
        "login":login,
        "password":password
    };

    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.JSON;
    myAjax.params = params;
    myAjax.success = function (data) {
        if(data.success === true){
            location.reload();
            // alert(data);
        }else{
            alert("Неверные данные для входа");
        }
    };
    myAjax.send(LOG_IN);
    return false;
}