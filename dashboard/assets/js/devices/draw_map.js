////////////////////////////////////////////////////////////////
//                        ЯНДЕКС.КАРТА                        //
////////////////////////////////////////////////////////////////

const GET_DEVICES_INFO = "get devices info";
ymaps.ready(init);

let myMap;

function init(){
    myMap = new ymaps.Map("map", {
        center: [59.93173718, 30.36081675], // [широта, долгота]
        zoom: 11 // от 0 (весь мир) до 19
    });

    $.ajax({
        method: 'POST',
        data: {
            action: GET_DEVICES_INFO,
            params: {
                company:$('#device-filter-company').val(),
                status:$('#device-filter-status').val()
            }
        },
        dataType: 'json',
        success: function (data) {
            for(let i=0; i<data.length; i++) {
                let coords = data[i].coords.split(" ");
                let coordH = coords[0];
                let coordW = coords[1];
                var myPlacemark = new ymaps.Placemark(
                    [coordH,coordW],

                    {
                        iconCaption: '№'+data[i].id,
                        balloonContentBody:
                        'Имя аппарата: №'+data[i].id+'<br>' +
                        'Группа устройств: '+data[i].company+'<br>' +
                        'Время последней отчистки: '+data[i].date_clear_basket+'<br>' +
                        'Заполненность корзины: ' + data[i].occupancy + '%' //**********************
                    },
                    {
                        // в зависимости от того, в в сети аппарат или нет,
                        // и насколько он заполнен, цвета меток будут
                        // либо красный (в сети, но переполнен >70%),
                        // либо зеленый (в сети и не переполнен), либо серый (не в сети)
                        // значения:
                        // islands#darkGreenDotIcon islands#redDotIcon islands#grayDotIcon
                        preset: 'islands#'+
                            // ((parseInt(data[i].occupancy) < 70 ? 'darkGreenDotIcon' : 'redDotIcon'))
                            (parseInt(data[i].occupancy) > 70 ? 'redDotIcon' : 
                                (parseInt(data[i].occupancy) > 50 ? 'yellowDotIcon' : 'darkGreenDotIcon'))
                    });

                myMap.geoObjects.add(myPlacemark);
            }
        }
    });
}

function mapUpd() {
    $.ajax({
        method: 'POST',
        data: {
            action: GET_DEVICES_INFO,
            params: {
                company: $('#device-filter-company').val(),
                status: $('#device-filter-status').val()
            }
        },
        dataType: 'json',
        success: function (data) {
            myMap.geoObjects.removeAll();
            for (let i = 0; i < data.length; i++) {
                let coords = data[i].coords.split(" ");
                let coordH = coords[0];
                let coordW = coords[1];
                var myPlacemark = new ymaps.Placemark(
                    [coordH, coordW],

                    {
                        iconCaption: '№' + data[i].id,
                        balloonContentBody:
                            'Имя аппарата: №' + data[i].id + '<br>' +
                            'Группа устройств: ' + data[i].company + '<br>' +
                            'Время последней отчистки: ' + data[i].date_clear_basket + '<br>' +
                            'Заполненность корзины: ' + data[i].occupancy + '%' //**********************
                    },
                    {
                        // в зависимости от того, в в сети аппарат или нет,
                        // и насколько он заполнен, цвета меток будут
                        // либо красный (в сети, но переполнен >70%),
                        // либо зеленый (в сети и не переполнен), либо серый (не в сети)
                        // значения:
                        // islands#darkGreenDotIcon islands#redDotIcon islands#grayDotIcon
                        preset: 'islands#' +
                            (parseInt(data[i].occupancy) > 70 ? 'redDotIcon' : 
                                (parseInt(data[i].occupancy) > 50 ? 'yellowDotIcon' : 'darkGreenDotIcon'))
                    });

                myMap.geoObjects.add(myPlacemark);
            }
        }
    });
}

////////////////////////////////////////////////////////////////
//                      ЯНДЕКС.КАРТА КОНЕЦ                    //
////////////////////////////////////////////////////////////////