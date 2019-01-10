(function($) {
    $(function () {
        $(document).ready(function() {
            //var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

            /*var categories =['9:00', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30'];
            var d1 = [[0,3], [1, 4], [2, 7], [3, 8], [4, 13], [5, 16], [6, 17], [7, 19], [8, 20]];
            var d2 = [];
            var d3 = [];

            for(var i = 0; i < (d1.length-1); i++) {
                var d1_val = d1[i][1];
                var d1_next_val = d1[i+1][1];

                var difference = d1_next_val - d1_val;
                d2.push([i+1, difference]);
            }

            d3[0] = d1[0];
            for(var i = 1; i < d1.length; i++) {
                //console.log('d1 = ' + d1[i][1] + ' | d2 = ' + d2[i-1][1]);
                d3.push([i, (d1[i][1] - d2[i-1][1])]);
            }

            for(var i = 0; i<d3.length; i++) {
                d3[i][0] = categories[i];
            }

            for(var i = 0; i<d2.length; i++) {
                d2[i][0] = categories[i+1];
            }

            //var data = [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ];

            // ------------- Рисуем график -------------------
            $.plot("#meter-chart", [d3, d2], {
                series: {
                    stack: 0,
                    bars: {
                        show: true,
                        barWidth: 0.9
                        //align: "center"
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });*/

            var previousPoint = null,
                previousLabel = null;

            var interval = 1; // to-do $('#interval').val()

            var select_interval = document.getElementById("select_add");
            var date_from_input = document.getElementById("date_from");
            var date_to_input = document.getElementById("date_to");
            var select_date_button = document.getElementById("select_date_button");

            //select_interval_onchange();
            select_interval.addEventListener("change", select_interval_onchange);
            select_interval_onchange();

            select_date_button.addEventListener("click", select_date_clicked);

            /*$form_data = {'interval' : interval,
                        'from_date' : '2018-12-25 10:00',
                        'to_date' : '2018-12-25 12:00',
                        'meter_id' : 8};
            makeSelectAjax(myScript.askue_plugin_url + "/askue/pages/meter_details/select_chart_values.php", $form_data);*/


            // --------------- Ajax запрос на выборку значений счетчика --------------------
            function makeSelectAjax(action, form_data) {
                console.log("make select ajax");
                jQuery.ajax({
                    type: 'POST',
                    url: action,
                    data: form_data
                }).done(function (response) {
                    console.log('response: ' + response);
                    var json_reponse =  jQuery.parseJSON(response);
                    //console.log('arr size = ' + Object.keys(json_reponse).length);
                    showDataSet(json_reponse);
                    /*for(var row in json_reponse) {
                        if(json_reponse.hasOwnProperty(row))
                            console.log('row: ' + json_reponse[row][1]);
                    }*/

                }).fail(function (data) {
                    console.log('delete error!');
                    if (data.responseText !== '') {
                        console.log('response: ' + data.responseText);
                    }
                });
            }

            // --------------- Формируем массив данных для графика --------------------
            function showDataSet(json_meter_values) {

                var arr_size = Object.keys(json_meter_values).length;
                var d2 = [];
                var d3 = [];

                if(arr_size > 0) {
                        d2.push([json_meter_values[0][0], json_meter_values[0][1]]);
                        for (var i = 0; i < (arr_size - 1); i++) {
                            //console.log(json_meter_values[i][0] + " - " + json_meter_values[i][1]);
                            var d1_val = json_meter_values[i][1];
                            var d1_next_val = json_meter_values[i + 1][1];

                            var difference = d1_next_val - d1_val;
                            d2.push([json_meter_values[i + 1][0], difference]);
                        }

                        for (var i = 0; i < arr_size; i++) {
                            //console.log('d1 = ' + d1[i][1] + ' | d2 = ' + d2[i-1][1]);
                            d3.push([json_meter_values[i][0], (json_meter_values[i][1] - d2[i][1])]);
                        }

                        drawBarsChart(d3, d2);
                }
            }

            // ------------- Рисуем график гистограмму -------------------
            function drawBarsChart(arr1, arr2) {
                $.plot("#meter-chart", [{label: "Прошлое потребление Кв/Ч", data: arr1}, {label: "Актуальное потребление Кв/Ч", data: arr2}], {
                    series: {
                        stack: 0,
                        bars: {
                            show: true,
                            barWidth: 0.9
                        }
                    },
                    xaxis: {
                        mode: "categories",
                        tickLength: 0
                    },
                    grid: {
                        hoverable: true,
                        clickable: true

                        //mouseActiveRadius: 30  //specifies how far the mouse can activate an item
                    },
                    valueLabels: {
                        show: true
                    }
                });
            }

            function showTooltip(x, y, color, contents) {
                $('<div id="tooltip">' + contents + '</div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 120,
                    border: '2px solid ' + color,
                    padding: '3px',
                    'font-size': '12px',
                    'border-radius': '5px',
                    'background-color': '#fff',
                    'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                    opacity: 0.9
                }).appendTo("body").fadeIn(200);
            }


            $("#meter-chart").on("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();

                        var x = item.datapoint[0];
                        var y = item.datapoint[1];

                        var color = item.series.color;

                        //console.log(item.series.xaxis.ticks[x].label);

                        showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> Кв/Ч");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });

            function select_interval_onchange() {

                var select_val = select_interval.options[select_interval.selectedIndex].value;
                console.log('select val: ' + select_val);

                var meter_last_date = document.getElementById("meter-value-last-date");
                //var meter_last_value_date = new Date(meter_last_date.value);

                var date_to = new Date(meter_last_date.value);
                var date_from = new Date(date_to);
                date_from.setHours(0, 0);

                //console.log('last meter value date = ' + meter_last_value_date);
                //console.log('last meter date = ' + meter_last_date);

                switch (select_val) {
                    case 'MINUTES_30':
                        interval = 1;
                        break;
                    case 'HOURS_1':
                        //date_from.setMonth(date_from.getMonth(), date_from.getDay() - 1);
                        date_from.setDate(date_from.getDate() - 1);
                        interval = 2;
                        break;
                    case 'DAY':
                        date_from.setDate(date_from.getDate() - 14);
                        interval = 4;
                        break;
                    case 'WEEK':
                        date_from.setDate(date_from.getDate() - 30);
                        interval = 5;
                        break;
                    case 'MONTH':
                        date_from.setDate(date_from.getDate() - 30*3);
                        interval = 6;
                        break;
                    default:
                        break;
                }

                var formatted_date_from = getFormattedDate(date_from);
                var formatted_date_to = getFormattedDate(date_to);

                $form_data = {'interval' : interval,
                    'from_date' : formatted_date_from,
                    'to_date' : formatted_date_to,
                    'meter_id' : findGetParameter("meter")};
                makeSelectAjax(myScript.askue_plugin_url + "/askue/pages/meter_details/select_chart_values.php", $form_data);

                //console.log('meter id = ' + findGetParameter("meter"));

                date_from_input.value = formatted_date_from;
                date_to_input.value = formatted_date_to;
                //console.log('interval = ' + interval + ' | date_from = ' + date_from + ' | date_to = ' + date_to);
            }


            function select_date_clicked() {

                var date_from = new Date(date_from_input.value);
                var date_to = new Date(date_to_input.value);

                var formatted_date_from = getFormattedDate(date_from);
                var formatted_date_to = getFormattedDate(date_to);

                $form_data = {'interval' : interval,
                    'from_date' : formatted_date_from,
                    'to_date' : formatted_date_to,
                    'meter_id' : findGetParameter("meter")};
                makeSelectAjax(myScript.askue_plugin_url + "/askue/pages/meter_details/select_chart_values.php", $form_data);

                //console.log(' | date_from = ' + date_from + ' | date_to = ' + date_to);

            }


            function getFormattedDate(date) {
                var day = date.getDate();
                if(day < 10) day = '0'+day;
                var month = (date.getMonth()+1);
                if(month < 10) month = '0'+month;
                var hour = (date.getHours());
                if(hour < 10) hour = '0'+hour;
                var minute = (date.getMinutes());
                if(minute < 10) minute = '0'+minute;

                return date.getFullYear() + '-' + month + '-' + day + 'T' + hour + ':' + minute;
            }

            function findGetParameter(parameterName) {
                var result = null,
                    tmp = [];
                var items = location.search.substr(1).split("&");
                for (var index = 0; index < items.length; index++) {
                    tmp = items[index].split("=");
                    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
                }
                return result;
            }
        });
    });
})(jQuery);