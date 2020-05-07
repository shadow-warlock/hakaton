const BARCODE_PAGING = "barcode paging";


function barcodesPaging(num) {
    let params = {
        "page_number":num,
        "filter":$('#barcodes_filter').val()
    };

    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.JSON;
    myAjax.params = params;
    myAjax.success = function (data) {
        $('#users-paging-container').html(data.pagination);
        $('#users-table-container').html(data.barcodes);
        selectAllBarcodes($('#select-all-barcodes'), '0');
        $('#select-all-barcodes').prop("checked", false);
    };
    myAjax.send(BARCODE_PAGING);
    return false;
}