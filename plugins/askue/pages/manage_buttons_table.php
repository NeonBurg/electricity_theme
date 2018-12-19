<?php if($access_level == 2 || $access_level == 3): ?>
<script type="text/javascript">

    function select_add_click(is_admin) {
        //console.log('select_add_click');
        var e = document.getElementById("select_add");
        var select_val = e.options[e.selectedIndex].value;

        switch (select_val) {
            case "meter_type":
                if(is_admin)
                    location.href = '/wp-admin/admin.php?page=add_meter_type';
                else
                    location.href = '/user-room/meters-management/add-meter-type';
                break;
            case "meter":
                if(is_admin)
                    location.href = '/wp-admin/admin.php?page=add_meter';
                else
                    location.href = '/user-room/meters-management/add-meter';
                break;
            case "energy_object":
                if(is_admin)
                    location.href = '/wp-admin/admin.php?page=add_energy_object';
                else
                    location.href = '/user-room/meters-management/add-energy-object';
                break;
            default:
                break;
        }
    }
</script>

<!-- buttons -->
<div class="askue-horizontal-buttons-containter">
    <div class="askue-horizontal-buttons-inner-left">

        <select id="select_add" name="select_add" class="askue-select-add">
            <?php if($access_level == 3): ?><option value="meter_type">Тип счетчика</option><?php endif; ?>
            <option value="meter">Счетчик</option>
            <option value="energy_object">Объект</option>
        </select>

        <div class="askue-button-wrap"><div class="askue-button" onclick="select_add_click(<?=is_admin()?>)">Добавить</div></div>
    </div>

    <div class="askue-horizontal-buttons-inner-right">
        <?php if(is_admin()):?>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_energy_object';">Добавить объект</div></div>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/wp-admin/admin.php?page=add_meter';">Добавить счетчик</div></div>
        <?php else: ?>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/user-room/meters-management/add-energy-object';">Добавить объект</div></div>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href='/user-room/meters-management/add-meter';">Добавить счетчик</div></div>
        <?php endif ?>
    </div>
</div>
<?php endif; ?>