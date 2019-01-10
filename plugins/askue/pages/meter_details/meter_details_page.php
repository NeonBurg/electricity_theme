<?php if(!isset($_GET["meter"]) || (isset($_GET["meter"]) && empty($_GET["meter"])) ): ?>
    <h1>Неверный адрес страницы</h1>
<?php else:?>

    <?php

    wp_enqueue_script('delete_ajax');
    wp_enqueue_script('jquery_flot');
    wp_enqueue_script('flot_categories');
    wp_enqueue_script('flot_stack');
    wp_enqueue_script('meter_chart');
    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

    global $wpdb;
    $dataController = new DataController($wpdb);
    if(is_admin()) $access_level = 3;

    $meter = null;
    $meterValuesList = array();
    $meter_last_date = "2019-01-02T21:21";

    if(isset($_GET["meter"])) {
        $meter_id = $_GET["meter"];
        if (!empty($meter_id)) {
            $meter = $dataController->selectMeter($meter_id);
            $meterValuesList = $dataController->selectMeterValuesList($meter_id);
            $meter_last_value = $dataController->selectMeterLastValue($meter_id);
            if($meter_last_value != null) {
                $meter_last_date = $meter_last_value->getDate();
            }
        }
    }

    ?>

    <style>
        .demo-meter-chart {
            width: 100%;
            height:500px;
        }
    </style>

<h1>Счетчик '<?=$meter->getName();?>'</h1>

    <?php
        /*$meter_values_list = $dataController->selectFilteredMeterValues(DataController::MINUTES_30, new DateTime("2018-12-25 10:00"), new DateTime("2018-12-25 12:00"), $meter_id);
        //echo count($meter_values_list);
        foreach($meter_values_list as $meter_value) {
            //echo $meter_value . " | ";
            echo $meter_value[1] . " - " . $meter_value[0]. " | ";
        }*/
    ?>

    <div class="chart-tools-container">
        <div class="chart-tool-title">Интервал: </div>
        <select id="select_add" name="select_add" class="askue-select-add">
            <option value="MINUTES_30">30 минут</option>
            <option value="HOURS_1">Час</option>
            <option value="DAY">День</option>
            <option value="WEEK">Неделя</option>
            <option value="MONTH">Месяц</option>
        </select>

        <div class="chart-tool-spacer"></div>
        <input type="datetime-local" id="date_from" class="chart-date-input">

        <div class="chart-tool-title"> - </div>
        <input type="datetime-local" id="date_to" class="chart-date-input">
        <input type="hidden" id="meter-value-last-date" value="<?=$meter_last_date?>">

        <button type="button" id="select_date_button" style="display: inline-block; height:29px;">Ок</button>
    </div>

    <div class="meter-chart-container">
        <div id="meter-chart" class="demo-meter-chart"></div>
    </div>

    <!---------- Meter Values Table ------------>
    <div class="accounts_container">
        <div class="groups_content">
            <div class="block_title">Показатели счетчика '<?=$meter->getName();?>'</div>

            <?php if(!is_admin()):?>
            <div class="user_room_block_border">
                <?php endif;?>

                <table class="energy-object-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="33%" style="text-align:left; padding-left:10px;">Значение</th>
                        <th>Дата</th>
                        <?php if($access_level == 3): ?>
                            <th width="6%"></th>
                        <?php endif; ?>
                    </tr>

                    <?php foreach ($meterValuesList as $meterValue):?>
                        <tr>
                            <td style="text-align:left; padding-left:10px;"><?php echo $meterValue->getValue();?></td>
                            <td><?=$meterValue->getFormattedDate();?></td>

                            <?php if($access_level == 3): ?>
                                <td>
                                    <div style="padding-top:6px;">
                                        <div style="display: inline-block;">
                                            <?php if(is_admin()): ?>
                                                <div class="edit-button" onclick="location.href='/wp-admin/admin.php?page=add_meter_value&meter=<?=$meter->getId();?>&edit=<?=$meterValue->getId();?>'"></div>
                                            <?php else: ?>
                                                <div class="edit-button" onclick="location.href='/user-room/meters-management/meter-details/add-meter-value?meter=<?=$meter->getId();?>&edit=<?=$meterValue->getId();?>'"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div style="display: inline-block; padding-left:5px;">
                                            <div class="delete-button" onclick="delete_meter_value(<?=$meter->getId()?>, <?=$meterValue->getId()?>, <?=$meterValue->getValue()?>, '<?=$meterValue->getFormattedDate()?>');"></div>
                                        </div>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>

                    <?php
                        if(count($meterValuesList) == 0) {
                            echo '<tr><td style="text-align:left; padding: 10px;">Пустой список показаний</td></tr>';
                        }
                    ?>
                </table>

                <?php if(!is_admin()):?>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <?php if($access_level == 3): ?>
        <div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="<?php if(is_admin()) echo "location.href='/wp-admin/admin.php?page=add_meter_value&meter=".$meter->getId()."'"; else echo "location.href='/user-room/meters-management/meter-details/add-meter-value?meter=".$meter->getId()."'"; ?>">Добавить показания</div></div></div>
    <?php endif; ?>

<?php endif; ?>