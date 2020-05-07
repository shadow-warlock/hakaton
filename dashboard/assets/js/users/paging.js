const USERS_PAGING = "users paging";

function usersPaging(num, region, role) {
    let params = {
        "page_number":num,
        "region_filter":region,
        "role_filter":role
    };

    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.JSON;
    myAjax.params = params;
    myAjax.success = function (data) {
        $('#users-paging-container').html(data.pagination);
        $('#users-table-container').html(data.users);
    };
    myAjax.send(USERS_PAGING);
    return false;
}