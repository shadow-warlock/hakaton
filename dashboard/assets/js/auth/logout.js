const LOG_OUT = "logout";

function logout() {
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.success = function (data) {
        location.reload();
    };
    myAjax.send(LOG_OUT);
}