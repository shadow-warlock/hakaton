const CREATE_DEVICE_OPEN_MODAL = "create device open modal";
const CREATE_DEVICE = "create device";
const DELETE_DEVICE = "delete device";
const UPDATE_DEVICE = "update device";
const UPDATE_DEVICE_BARCODES = "update device barcodes";
const UPDATE_DEVICE_COUPONS = "update device coupons";
const OPEN_UPDATE_DEVICE = "open update device";
const OPEN_EYE_DEVICE = "open eye device";
const DEVICE_CHANGE_ON_OFF = "device change on off";
const CHECK_OCCUPANCY = "check occupancy";

function deviceChangeOnOff(id) {
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        devicesPaging($(".paging-button.toolbar-button.selected").text());
    };
    myAjax.send(DEVICE_CHANGE_ON_OFF);
}



function openDeviceAddModal() {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(CREATE_DEVICE_OPEN_MODAL);
}

function updateDevice(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        devicesPaging($(".paging-button.toolbar-button.selected").text())
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете логин, который уже есть в базе");
    };
    myAjax.send(UPDATE_DEVICE);
    return false;
}

function openUpdateDevice(id) {
    modal.open();
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.params = params;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(OPEN_UPDATE_DEVICE);
}

function checkOccupancy(id) {
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(data){
        alert("Команда отправлена");
        modal.close();
    };
    myAjax.send(CHECK_OCCUPANCY);
}

function updateDeviceBarcodes(id) {
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(data){
        alert("База успешно обновлена");
        modal.close();
    };
    myAjax.send(UPDATE_DEVICE_BARCODES);
}

function updateDeviceCoupons(id) {
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(data){
        alert("База успешно обновлена");
        modal.close();
    };
    myAjax.send(UPDATE_DEVICE_COUPONS);
}

function deviceEye(id) {
    modal.open();
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.params = params;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(OPEN_EYE_DEVICE);
}

function addDevice(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете название устройства, который уже есть в базе");
    };
    myAjax.send(CREATE_DEVICE);
    return false;
}

function deleteDevice(id) {
    if(confirm("Вы уверены, что хотите удалить устройство с именем " + id)){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        let params = {"id":id};
        myAjax.params = params;
        myAjax.success = function(){
            location.reload();
        };
        myAjax.send(DELETE_DEVICE);
    }
}