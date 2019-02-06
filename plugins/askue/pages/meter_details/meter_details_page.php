<?php if(!isset($_GET["meter"]) || (isset($_GET["meter"]) && empty($_GET["meter"])) ): ?>
    <h1>Неверный адрес страницы</h1>
<?php else:?>

    <script>
        /*var is_up_sort = false;

        function sort_clicked() {



        }*/
    </script>

    <?php

    wp_enqueue_script('delete_ajax');
    wp_enqueue_script('jquery_flot');
    wp_enqueue_script('flot_categories');
    wp_enqueue_script('flot_stack');
    wp_enqueue_script('meter_chart');

    wp_enqueue_script('change_page');
    wp_enqueue_script('meter_values_page_ajax');
    

    require_once(ASKUE_PLUGIN_DIR . "models/DataController.php");

    global $wpdb;
    $dataController = new DataController($wpdb);
    if(is_admin()) define("ACCESS_LEVEL", 3);

    $meter = null;
    $meterValuesList = array();
    $meter_last_date = "2019-01-02T21:21";

    $currentValue = 0;

    if(isset($_GET["meter"])) {
        $meter_id = $_GET["meter"];
        if (!empty($meter_id)) {
            $meter = $dataController->selectMeter($meter_id);
            $meterValuesList = $dataController->selectMeterValuesList($meter_id);
            $meter_last_value = $dataController->selectMeterLastValue($meter_id);
            if($meter_last_value != null) {
                $meter_last_date = $meter_last_value->getDate();
            }
            $meterLastValue = $dataController->selectMeterLastValue($meter_id);
            if($meterLastValue) {
                $currentValue = $dataController->selectMeterLastValue($meter_id)->getValue();
            }
        }
    }

    ?>

    <style>
        .demo-meter-chart {
            width: 100%;
            height:500px;
        }

        #legend-container {
            z-index:1000;
            position:absolute;
            margin-left:40px;
            margin-top:15px;
        }

        #last-chart-category {
            width:50px;
            position:relative;
            float:right;
            bottom: 20px;
            left: 15px;
            color: #575757;
            font-size: 9pt;
            font-family: 'Open Sans', sans-serif;
        }

        #meter-chart{
            width:100%;
            font-family: 'Open Sans', sans-serif;
        }
    </style>

<h1>Счетчик '<?=$meter->getName();?>'</h1>

    <input type="hidden" id="meter_id" value="<?=$meter_id?>">
    <input type="hidden" id="access_level" value="<?=ACCESS_LEVEL?>">

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

    <div class="meter-chart-container" id="meter-chart-container">
        <div id="legend-container"></div>
        <div id="meter-chart" class="demo-meter-chart"></div>
        <div id="last-chart-category"></div>
    </div>


    <div style="height:15px;"></div>

    <?php include(ASKUE_PLUGIN_DIR."pages/statistics_block.php"); ?>

<div id="search_error_msg" style="color:red; margin-top:10px;"></div>

<div class="meters_table_container">

    <!---------- Meter Values Table ------------>
    <div class="accounts_container">
        <div class="groups_content">
            <div class="block_title">Показатели счетчика '<?=$meter->getName();?>'</div>

            <?php if(!is_admin()):?>
            <div class="user_room_block_border">
                <?php endif;?>

                <table class="energy-object-table" id="meter-values-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="33%" style="text-align:left; padding-left:10px;">Значение</th>
                        <th>
                            <div class="sort_container" onclick="sort_clicked()">
                                <div class="sort_icon_container"><div class="sort_down_icon" id="sort_icon"></div></div>
                                <div class="meter_date_title">Дата</div>
                            </div>
                        </th>
                        <?php if(ACCESS_LEVEL == 3): ?>
                            <th width="6%"></th>
                        <?php endif; ?>
                    </tr>

                    <!--<?php foreach ($meterValuesList as $meterValue):?>

                        <?php
                            $value_edit_url_admin = site_url('/wp-admin/admin.php?page=add_meter_value&meter='.$meter->getId().'&edit='.$meterValue->getId());
                            $value_edit_url_user_room = site_url('/user-room/meters-management/meter-details/add-meter-value?meter='.$meter->getId().'&edit='.$meterValue->getId());
                        ?>

                        <tr>
                            <td style="text-align:left; padding-left:10px;"><?php echo $meterValue->getValue();?></td>
                            <td><?=$meterValue->getFormattedDate();?></td>

                            <?php if(ACCESS_LEVEL == 3): ?>
                                <td>
                                    <div style="padding-top:6px;">
                                        <div style="display: inline-block;">
                                            <?php if(is_admin()): ?>
                                                <div class="edit-button" onclick="location.href='<?=$value_edit_url_admin?>'"></div>
                                            <?php else: ?>
                                                <div class="edit-button" onclick="location.href='<?=$value_edit_url_user_room?>'"></div>
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
                    ?>-->
                </table>

                <?php if(!is_admin()):?>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <!----------------- Select pages ------------------->
    <div class="page_coinainer">
        <div class="page_select_block">
            <div id="pages_row"></div>
            <select id="page_number_select" onchange="selectPageChange()"></select>
        </div>
        <div class="items_on_page_block">
            Записей на странице:
            <div style="display:inline-block;">
                <select id="items_on_page_select" onchange="selectItemsOnPageChange()"></select>
            </div>
        </div>
    </div>

    <?php if(ACCESS_LEVEL == 3): ?>
        <div style="width:100%; display: inline-block;"><div class="add_group_button_wrap"><div class="askue-button" onclick="<?php if(is_admin()) echo "location.href='".site_url('/wp-admin/admin.php?page=add_meter_value&meter='.$meter->getId())."'"; else echo "location.href='".site_url('/user-room/meters-management/meter-details/add-meter-value?meter='.$meter->getId())."'"; ?>">Добавить показания</div></div></div>
    <?php endif; ?>
</div>

<?php endif; ?>