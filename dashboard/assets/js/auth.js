const LOG_IN = "log in";

function authSubmit() {
    let login = $('auth-login-input').val();
    let password = $('auth-pass-input').val();
    $.ajax({
        method:'POST',
        data:{
            action:LOG_IN,
            params:{
                login:login,
                password:password
            }
        }
    }).done(function (data) {
        alert(data);
    });
    return false;
}