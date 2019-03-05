(function($) {
    $(function () {
        $(document).ready(function() {

            var previousPoint = null,
                previousLabel = null;

            var interval = 1; // to-do $('#interval').val()
            var is_date_selected = false;

            var select_interval = document.getElementById("select_add");
            var date_from_input = document.getElementById("date_from");
            var date_to_input = document.getElementById("date_to");
            var select_date_button = document.getElementById("select_date_button");

            //select_interval_onchange();
            select_interval.addEventListener("change", select_interval_onchange);
            select_interval_onchange();

            select_date_button.addEventListener("click", select_date_clicked);


            // --------------- Ajax запрос на выборку значений счетчика --------------------
            function makeSelectAjax(action, form_data) {
                console.log("make select ajax");
                jQuery.ajax({
                    type: 'POST',
                    url: action,
                    data: form_data
                }).done(function (response) {
                    //console.log('response: ' + response);
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

                var old_value = 0;

                if(arr_size > 0) {
                        d2.push([json_meter_values[0][0], json_meter_values[0][1]]);
                        for (var i = 0; i < (arr_size - 2); i++) {
                            //console.log(json_meter_values[i][0] + " - " + json_meter_values[i][1]);
                            //console.log(json_meter_values[i+1][0] + " - " + json_meter_values[i+1][1]);
                            var d1_val = json_meter_values[i][1];
                            var d1_next_val = json_meter_values[i + 1][1];

                            var difference = d1_next_val - d1_val;

                            if(d1_val !== 0) old_value = d1_val;
                            else {
                                difference = d1_next_val - old_value;
                            }
                            console.log('difference = ' + difference);
                            if(difference < 0) {
                                d2.push([json_meter_values[i + 1][0], json_meter_values[i + 1][1]]);
                            }
                            else {
                                d2.push([json_meter_values[i + 1][0], difference]);
                            }
                        }

                        /*for (var i = 0; i < arr_size-1; i++) {
                            d3.push([json_meter_values[i][0], (json_meter_values[i][1] - d2[i][1])]);
                            //console.log('d3 = ' + d3[i][1]);
                        }*/

                        //d3.push()

                        drawBarsChart(d2, json_meter_values[arr_size-1][0]);
                }
            }

            // ------------- Рисуем график гистограмму -------------------
            function drawBarsChart(arr1, last_val) {
                var p = $.plot("#meter-chart", [{label: "Актуальное потребление Кв/Ч", data: arr1}], {
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
                    },
                    legend:{
                        container:$("#legend-container")
                    },
                });

                /*if(p.getData()[0].data.length > 20) {
                    $.each(p.getData()[0].data, function(i, el){
                        if(i%2===0) {
                            //console.log('el['+i+']= ' + el);
                        }
                    });
                }*/

                var chart_elements = p.getData()[0].data.length;
                var interval = parseInt(chart_elements/15);


                //console.log('chart_elements = ' + chart_elements);

                var chart_label_width = parseInt($('.flot-x-axis .flot-tick-label').css("width"));
                var chart_width = parseInt($("#meter-chart-container").css("width"));
                var chart_label_height = parseInt($('.flot-x-axis .flot-tick-label').css("height"));
                var all_labels_width = chart_label_width*chart_elements;

                console.log('chart_width = ' + chart_width);
                console.log('chart_elements = ' + chart_elements);
                console.log('chart_label_width = ' + chart_label_width);
                console.log('all labels width = ' + all_labels_width);
                console.log('statement: ' + (chart_width - all_labels_width));

                var is_shortcut_labels = false;
                if((chart_width - all_labels_width) < 150 || chart_label_width < 10) {
                    is_shortcut_labels = true;
                }

                console.log('is_shortcut_labels: ' + is_shortcut_labels);

                if(is_shortcut_labels) {
                    if(interval <= 1) interval = 2;
                    $('.flot-x-axis .flot-tick-label').each(function (index) {
                        //$(this).css("display","none");
                        if(index%interval!==0) {
                            //console.log('css width = ' + $(this).css("width"));
                            $(this).css("display","none");
                            //console.log('index = ' + index);
                        }
                        else {
                            if(is_shortcut_labels) {
                                console.log('is_shortcut_labels');
                                $(this).css("width", "50px!important");
                                $(this).css("height", "50px!important");
                            }
                        }
                        //char_label_width = parseInt($(this).css("width"));
                    });
                    //document.getElementById("last-chart-category").innerHTML = "";

                    //document.getElementById("last-chart-category").innerHTML = last_val; //<---- Last_val
                }
                /*else {
                    //last-chart-category
                    document.getElementById("last-chart-category").innerHTML = last_val;
                }*/

                document.getElementById("last-chart-category").innerHTML = last_val;

                //console.log('char_label_height = ' + chart_label_height);

                    if(chart_label_height > 18) {
                        document.getElementById("last-chart-category").style.width = "50px";
                        document.getElementById("last-chart-category").style.bottom = "38px";
                    }
                    else {
                        document.getElementById("last-chart-category").style.bottom = "20px";
                    }

                //document.getElementById("last-chart-category").innerHTML = date_to_input.value;

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

                if(is_date_selected) {
                    date_to = new Date(date_to_input.value);
                    date_from = new Date(date_from_input.value);
                }

                //console.log('last meter value date = ' + meter_last_value_date);
                //console.log('last meter date = ' + meter_last_date);

                switch (select_val) {
                    case 'MINUTES_5':
                        interval = 7;
                        break;
                    case 'MINUTES_15':
                        interval = 8;
                        break;
                    case 'MINUTES_30':
                        interval = 1;
                        break;
                    case 'HOURS_1':
                        //date_from.setMonth(date_from.getMonth(), date_from.getDay() - 1);
                        //date_from.setDate(date_from.getDate());
                        interval = 2;
                        break;
                    case 'DAY':
                        date_from.setDate(date_from.getDate() - 14);
                        interval = 4;
                        break;
                    case 'WEEK':
                        date_from.setDate(date_from.getDate() - 30);
                        date_from.setDate(1);
                        interval = 5;
                        break;
                    case 'MONTH':
                        date_from.setDate(date_from.getDate() - 30*3);
                        date_from.setDate(1);
                        interval = 6;
                        break;
                    default:
                        break;
                }

                var formatted_date_from = getFormattedDate(date_from);
                var formatted_date_to = getFormattedDate(date_to);

                console.log('formatted_date_from = ' + formatted_date_from);

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

                is_date_selected = true;

                var date_from = new Date(date_from_input.value);
                var date_to = new Date(date_to_input.value);

                var formatted_date_from = getFormattedDate(date_from);
                var formatted_date_to = getFormattedDate(date_to);

                console.log('formatted_date_to = ' + formatted_date_to);

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