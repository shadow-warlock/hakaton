class Modal{



    open() {
        $('.modal-content-container').html('Загрузка...');
        $('#my-modal').fadeIn(200);
    }

    close() {
        $('#my-modal').fadeOut(200);
        $('.modal-content-container').html('');
    }



    getModalContent(keyword) {
        let id = this.id;
        $.ajax({
            url: "http://test.pandomat.ru/rivza/ajax_test.php",
            type: 'POST',
            data: {
                keyword: keyword
            },
            dataType: 'html',
            success: function (data) {
                $('#'+id+' .modal-content-container').html(data);
            }
        });
    }

    setContent(content) {
        $('.modal-content-container').html(content);

    }
}

let modal = new Modal();
