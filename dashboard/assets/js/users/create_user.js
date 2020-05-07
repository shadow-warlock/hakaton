const CREATE_USER_MODAL_OPEN = "create user modal open";
const UPDATE_USER_MODAL_OPEN = "update user modal open";
const CREATE_COMPANY = "create company";
const CREATE_REGION = "create region";
const CREATE_USER = "create user";
const DELETE_USER = "delete user";
const UPDATE_USER = "update user";

function createUserClick() {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(CREATE_USER_MODAL_OPEN);
}

function updateUserClick(id) {
    modal.open();
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.HTML;
    let params = {"id":id};
    myAjax.params = params;
    myAjax.success = function(data){
        modal.setContent(data);
    };
    myAjax.send(UPDATE_USER_MODAL_OPEN);
}

function deleteUser(id) {
    if(confirm("Вы точно хотите удалить пользователя с id " + id)){
        let myAjax = new MyAjax();
        myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
        let params = {"id":id};
        myAjax.params = params;
        myAjax.success = function(){
            location.reload();
        };
        myAjax.send(DELETE_USER);
    }
}

function createUser(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();

    };
    myAjax.typeErrorMessage= function (data, type) {
        console.log(data);
        alert("Ошибка при выполнении команды, вероятно, вы дублируете логин, который уже есть в базе");
    };
    myAjax.send(CREATE_USER);
    return false;
}

function updateUser(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        usersPaging($(".paging-button.toolbar-button.selected").text(), $('#users-region-filter').val(), $('#users-role-filter').val())
    };
    myAjax.typeErrorMessage= function (data, type) {
        alert("Ошибка при выполнении команды, вероятно, вы дублируете логин, который уже есть в базе");
    };
    myAjax.send(UPDATE_USER);
    return false;
}

function createCompany(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();

    };
    myAjax.send(CREATE_COMPANY);
    return false;
}

function createRegion(params) {
    params = objectifyForm(params);
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        modal.close();
        location.reload();
    };
    myAjax.send(CREATE_REGION);
    return false;
}