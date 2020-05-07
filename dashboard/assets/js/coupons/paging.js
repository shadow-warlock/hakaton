const COUPONS_PAGING = "coupons paging";

function couponsPaging(num) {
    let params = {
        "page_number":num,
        "name_filter":$('#coupons-name-filter').val(),
        "partner_filter":$('#coupons-partner-filter').val(),
        "sort":$('#coupons-sort').val()
    };

    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.JSON;
    myAjax.params = params;
    myAjax.success = function (data) {
        $('#users-paging-container').html(data.pagination);
        $('#users-table-container').html(data.devices);
        selectAllBarcodes($('#select-all-barcodes'), '0');
        $('#select-all-barcodes').prop("checked", false);
    };
    myAjax.send(COUPONS_PAGING);
    return false;
}