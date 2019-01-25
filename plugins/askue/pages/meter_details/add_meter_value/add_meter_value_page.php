<?php if(isset($access_level) && $access_level == 3 || is_admin()):?>

    <?php
        $meter_id = $_GET["meter"];
        $edit_meter_value = null;

        if(isset($meter_id)) {
            require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");
            wp_enqueue_script('donetype_script');

            global $wpdb;
            $dataController = new DataController($wpdb);
            $meter = $dataController->selectMeter($meter_id);

            if(isset($_GET["edit"])) {
                $edit_value_id = $_GET["edit"];
                $edit_meter_value = $dataController->selectMeterValue($meter_id, $edit_value_id);
            }
        }
    ?>

    <script type="text/javascript">
        var fields_count = 0;

        function delete_field_clicked(field_id) {
            //$('#value_row_'+field_id).remove();
            var element = document.getElementById("value_row_"+field_id);
            element.parentNode.removeChild(element);
        }

        function add_field_clicked() {
            //console.log('add_field_clicked;')
            var html_table_row = '<tr id="value_row_'+fields_count+'">' +
                '                <td width="50%" style="padding-right:50px;">' +
                '                    <!-- Value input: -->' +
                '        <div style="display: inline-block; width:100%;">' +
                '            <input type="text" name="meter_value_input_'+fields_count+'" id="meter_value_input_'+fields_count+'" class="askue_input" autocomplete="off" placeholder="Значение портребления электричества"';
                if(document.getElementById("edit_value_val")) {
                    html_table_row += 'value="' +document.getElementById("edit_value_val").value+'"';
                }
            html_table_row += '>' +
                '        </div>' +
                '    </td>' +
                '    <td width="50%" style="padding-right:7px;">' +
                '        <!-- Date input: -->' +
                '        <div style="display: inline-block; width:100%;">' +
                '                        <input type="datetime-local" name="meter_date_input_'+fields_count+'" id="meter_date_input_'+fields_count+'" class="askue_input" autocomplete="off"';
                if(document.getElementById("edit_value_date")) {
                    var date = new Date(document.getElementById("edit_value_date").value);
                    var day = date.getDate();
                    if(day < 10) day = '0'+day;
                    var month = (date.getMonth()+1);
                    if(month < 10) month = '0'+month;
                    var hour = (date.getHours());
                    if(hour < 10) hour = '0'+hour;
                    var minute = (date.getMinutes());
                    if(minute < 10) minute = '0'+minute;
                    var formated_date = date.getFullYear() + '-' + month + '-' + day + 'T' + hour + ':' + minute;
                    html_table_row += 'value="' +formated_date+'"';
                }
                html_table_row += '>' +
                '        </div>' +
                        '</td>';
            if(fields_count > 0) {
                html_table_row += '<td style="padding-left:10px;"><button type="button" style="display:inline-block; width:25px; height:25px; float:right;" onclick="delete_field_clicked('+fields_count+')">-</button></td>';
            }
            html_table_row += '</tr>';

            //$('#meter_values_table').append(html_table_row);
            document.getElementById("meter_values_table").insertAdjacentHTML('beforeend', html_table_row);
            fields_count++;
        }

        jQuery(document).ready(function($) {
            add_field_clicked();

            var form = $('#ajax-add-meter-value');

            $(form).submit(function(event) {
                // Stop the browser from submitting the form.
                event.preventDefault();
                var formData = $(form).serialize();
                formData += '&fields_count='+fields_count;
                //console.log(formData);


                makeAjaxPost(myScript.askue_plugin_url + "/askue/pages/meter_details/add_meter_value/final_check.php", formData);

            });

            function makeAjaxPost(action, form_data) {
                $.ajax({
                    type: 'POST',
                    url: action,
                    data: form_data
                }).done(function(response) {
                    window.location.href = $("#meter_link").val();
                }).fail(function(data) {
                    if (data.responseText !== '') {
                        console.log('response: ' + data.responseText);
                        $('#error_msg').html(data.responseText);
                    }
                });
            }
        });
    </script>

    <h1>
        <!--<a href="/user-room/meters-management/meter-details?meter=<?=$meter_id?>">Счетчик '<?=$meter->getName();?>'</a> »-->
        <?php
            if(is_admin()) {
                $link = site_url('/wp-admin/admin.php?page=meter_details&meter='.$meter_id);
            }
            else {
                $link = site_url('/user-room/meters-management/meter-details?meter='.$meter_id);
            }

            echo '<a href="'.$link.'">Счетчик \''.$meter->getName().'\'</a> » ';
            if($edit_meter_value)
                echo 'Редактирование показаний';
            else
                echo 'Добавление показаний';
        ?>
    </h1>

    <?php if(!$edit_meter_value):?>
        <div class="plus_minus_container">
            <div style="display: inline-block; margin-right:10px;">Добавить поле:</div><button type="button" class="plus_minus_button" onclick="add_field_clicked()">+</button>
        </div>
    <?php endif;?>

<!-- Форма добавления нового счетчика -->
<form method="post" action="" id="ajax-add-meter-value">
    <input type="hidden" name="meter_id" id="meter_id" value="<?=$meter_id;?>">
    <input type="hidden" id="meter_link" value="<?=$link?>">
    <?php if($edit_meter_value) {
        echo '<input type="hidden" id="edit_value_id" name="edit_value_id" value="' . $edit_meter_value->getId() . '">';
        echo '<input type="hidden" id="edit_value_val" name="edit_value_val" value="' . $edit_meter_value->getValue() . '">';
        echo '<input type="hidden" id="edit_value_date" name="edit_value_date" value="' . $edit_meter_value->getDate() . '">';
    }
    ?>
    <div class="input-form-container">
        <table class="inputs-vertical-table" id="meter_values_table" cellspacing="0" cellpadding="0">

            <tr>
                <th>Показатели счетчика:</th>
                <th>Дата и время снятия показаний:</th>
            </tr>
        </table>
    </div>

    <!---------------- Submit button ----------------->
    <table class="askue-submit-button-table" cellpadding="0" cellspacing="0">
        <tr>
            <td><div id="error_msg" style="color:red;"></div></td>
            <td align="right">
                <input type="submit" class="askue-submit-button" <?php if($edit_meter_value) echo 'value="Сохранить"'; else echo 'value="Добавить"'; ?>>
            </td></tr>
    </table>
</form>

<?php else: ?>
    <div class="edit-title">Нет доступа к данной странице</div>
<?php endif; ?>
