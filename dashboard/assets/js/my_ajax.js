class MyAjax {

    constructor() {
        this.url = '';
        this.method = 'post';
        this.async = true;
        this.success = function () {};
        this.error = function () {};
        this.typeErrorMessage = function (data, type) {
            console.log(data);
            alert("Ответ запроса отличается от ожидаемого "+type+"\n (см. консоль)")
        };
        this.REQUEST_TYPES = {
            ALL: 'all',
            HTML: 'html',
            JSON: 'json',
            EMPTY: 'empty'
        };
        this.request = this.REQUEST_TYPES.ALL;
        this.params = {};

    }

    static isJsonString(str) {
        try {
            return JSON.parse(str);
        } catch (e) {
            return false;
        }
    }

    send(action) {
        let self = this;
        $.ajax({
            async: this.async,
            url: this.url,
            method: this.method,
            data: {
                action: action,
                params: this.params
            },
            request: this.request,
            success: function (data) {
                switch (self.request) {
                    case self.REQUEST_TYPES.EMPTY: {
                        if(data!=''){
                            self.typeErrorMessage(data, "\'\'");
                            return;
                        }
                        break;
                    }
                    case self.REQUEST_TYPES.HTML: {
                        if(data===''){
                            self.typeErrorMessage(data, "html");
                        }
                        break;
                    }
                    case self.REQUEST_TYPES.JSON: {
                        let message = MyAjax.isJsonString(data);
                        if(message === false){
                            console.log(data);
                            self.typeErrorMessage(data, "json");
                            return;
                        }
                        data = message;
                        break;
                    }
                }
                self.success(data);
            },
            error: function (arr) {
                console.log(arr);
                alert("Возникли ошибки при обработке запроса (см. консоль)");
                self.error(arr);
            }
        })
    }
}
