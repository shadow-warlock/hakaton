
const DELETE_COUPON = "delete coupon";
const ADD_COUPON_OPEN_MODAL = "add coupon open modal";
const EDIT_COUPON_OPEN_MODAL = "edit coupon open modal";
const PACKET_DELETE_COUPONS = "packet delete coupons";
const CREATE_COUPON = "create coupon";
const EDIT_COUPON = "edit coupon";
const COUPONS_RELOAD = "coupons reload";

function deleteCoupon(id, number){
    if(confirm("Вы уверены что хотите удалить купон с номером " + number)){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        let params = {"id":id};
        myAjax.params = params;
        myAjax.success = function(){
            location.reload();
        };
        myAjax.send(DELETE_COUPON);
    }
}


function couponsReload() {
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.success = function(){
        alert("Команда успешно выполнена, загрузка начнется при ближайшей сессии устройств с сервисом.");
    };
    myAjax.send(COUPONS_RELOAD);
}

function addCoupon(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете номер купона, который уже есть в базе");
    };
    myAjax.send(CREATE_COUPON);
    return false;
}

function editCoupon(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        couponsPaging($(".paging-button.toolbar-button.selected").text());
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете номер купона, который уже есть в базе");
    };
    myAjax.send(EDIT_COUPON);
    return false;
}

function addCouponOpenModal() {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.success = function(data){
        modal.setContent(data);
        $( ".datepicker" ).datepicker({
            dateFormat: "dd.mm.yy",
            dayNames: [ "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
            firstDay: 1,
            monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            autoSize: true
        });
    };
    myAjax.send(ADD_COUPON_OPEN_MODAL);
}

function editCouponOpenModal(id) {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.params = {"id":id};
    myAjax.success = function(data){
        modal.setContent(data);
        $( ".datepicker" ).datepicker({
            dateFormat: "dd.mm.yy",
            dayNames: [ "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
            firstDay: 1,
            monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            autoSize: true
        });
    };
    myAjax.send(EDIT_COUPON_OPEN_MODAL);
}

function packetDeleteCoupons() {
    let ids = [];
    $('tr[data-selected=1]').each(function (index, value) {
        ids.push($(value).attr("data-id"));
    });
    if(confirm("Вы уверены, что хотите удалить выбранные купоны?")){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        myAjax.params = ids;
        myAjax.success = function(){
            modal.close();
            location.reload();
        };
        myAjax.send(PACKET_DELETE_COUPONS);
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