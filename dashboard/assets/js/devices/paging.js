const DEVICES_PAGING = "devices paging";

function devicesPaging(num) {
    let params = {
        "page_number":num,
        "company_filter":$('#device-filter-company').val(),
        "status_filter":$('#device-filter-status').val()
    };

    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.JSON;
    myAjax.params = params;
    myAjax.success = function (data) {
        $('#users-paging-container').html(data.pagination);
        $('#users-table-container').html(data.devices);
    };
    myAjax.send(DEVICES_PAGING);
    return false;
}