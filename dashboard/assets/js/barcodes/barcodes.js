const CREATE_BARCODE_OPEN_MODAL = "create barcode open modal";
const UPDATE_BARCODE_OPEN_MODAL = "update barcode open modal";
const CREATE_BARCODE = "create barcode";
const EDIT_BARCODE = "edit barcode";
const PACKET_DELETE_BARCODES = "packet delete barcodes";
const DELETE_BARCODE = "delete barcode";
const PACKAGE_IMPORT_BARCODES_OPEN_MODAL = "package import barcodes open modal";
const BARCODES_RELOAD = "barcodes reload";


function sendBarcodesPackage() {
    let formData = new FormData();
    formData.append("myFile", document.getElementById("import-barcode-file").files[0], 'barcodes.csv');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "load_csv.php");
    xhr.send(formData);
    modal.close();
    setTimeout(function () {
        location.reload();
    }, 5000);
}

function barcodesReload() {
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.success = function(){
        alert("Команда успешно выполнена, загрузка начнется при ближайшей сессии устройств с сервисом.");
    };
    myAjax.send(BARCODES_RELOAD);
}

function packageImportBarcodesOpenModal() {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(PACKAGE_IMPORT_BARCODES_OPEN_MODAL);
}

function packetDeleteBarcodes() {
    let ids = [];
    $('tr[data-selected=1]').each(function (index, value) {
        ids.push($(value).attr("data-id"));
    });
    if(confirm("Вы уверены, что хотите удалить выбранные штрихкоды?")){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        if(ids.length > 0)
            myAjax.params = ids;
        else
            myAjax.params = ids;
        myAjax.success = function(){
            modal.close();
            location.reload();
        };
        myAjax.send(PACKET_DELETE_BARCODES);
    }
}

function deleteBarcode(id) {

    if(confirm("Вы уверены, что хотите удалить штрихкод номер "+id+"?")){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        myAjax.params = {"id":id};
        myAjax.success = function(){
            modal.close();
            location.reload();
        };
        myAjax.send(DELETE_BARCODE);
    }
}

function selectBarcode(tr, event) {
    if($(event.target).closest(".editor-cell").length > 0)
        return;
    let isSelected = parseInt($(tr).attr('data-selected'));
    if(isSelected) {
        $(tr).attr('data-selected','0');
    }
    else {
        $(tr).attr('data-selected','1');
    }
    makeTrashBarcose();
}

function makeTrashBarcose() {
    if($('tr[data-selected=1]').length > 0) {
        $('#delete-barcodes').removeClass('hidden-input');
    }
    else {
        $('#delete-barcodes').addClass('hidden-input');
    }
}

function selectAllBarcodes(box, set = null) {
    if(set == null){
        let isChecked = $(box).prop('checked');

        if(isChecked) {
            $('.barcodes-tr').attr('data-selected','1');
        }
        else {
            $('.barcodes-tr').attr('data-selected','0');
        }
    }else{
        $('.barcodes-tr').attr('data-selected', set);
    }


    makeTrashBarcose();
}

function addBarcodeOpenModal() {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(CREATE_BARCODE_OPEN_MODAL);
}

function addBarcode(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете номер штрихкода, который уже есть в базе");
    };
    myAjax.send(CREATE_BARCODE);
    return false;
}

function editBarcode(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        barcodesPaging($(".paging-button.toolbar-button.selected").text());
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете номер штрихкода, который уже есть в базе");
    };
    myAjax.send(EDIT_BARCODE);
    return false;
}

function editBarcodeOpenModal(id) {
    modal.open();
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.params = params;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(UPDATE_BARCODE_OPEN_MODAL);
}