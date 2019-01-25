<?php if(ACCESS_LEVEL == 2 || ACCESS_LEVEL == 3): ?>
<script type="text/javascript">

    function select_add_click(is_admin) {
        //console.log('select_add_click');
        var e = document.getElementById("select_add");
        var select_val = e.options[e.selectedIndex].value;
        var site_url = document.getElementById("site_url").value;

        switch (select_val) {
            case "meter_type":
                if(is_admin)
                    location.href = site_url + '/wp-admin/admin.php?page=add_meter_type';
                else
                    location.href = site_url + '/user-room/meters-management/add-meter-type';
                break;
            case "meter":
                if(is_admin)
                    location.href = site_url + '/wp-admin/admin.php?page=add_meter';
                else
                    location.href = site_url + '/user-room/meters-management/add-meter';
                break;
            case "energy_object":
                if(is_admin)
                    location.href = site_url + '/wp-admin/admin.php?page=add_energy_object';
                else
                    location.href = site_url + '/user-room/meters-management/add-energy-object';
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
            <?php if(ACCESS_LEVEL == 3): ?><option value="meter_type">Тип счетчика</option><?php endif; ?>
            <option value="meter">Счетчик</option>
            <option value="energy_object">Объект</option>
        </select>

        <input type="hidden" id="site_url" value="<?=site_url()?>">

        <div class="askue-button-wrap"><div class="askue-button" onclick="select_add_click(<?=is_admin()?>)">Добавить</div></div>
    </div>

    <div class="askue-horizontal-buttons-inner-right">
        <?php if(is_admin()):?>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href=<?='\''.site_url('/wp-admin/admin.php?page=add_energy_object').'\''?>;">Добавить объект</div></div>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href=<?='\''.site_url('/wp-admin/admin.php?page=add_meter').'\''?>;">Добавить счетчик</div></div>
        <?php else: ?>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href=<?='\''.site_url('/user-room/meters-management/add-energy-object').'\''?>;">Добавить объект</div></div>
            <div class="askue-button-wrap"><div class="askue-button" onclick="location.href=<?='\''.site_url('/user-room/meters-management/add-meter').'\''?>;">Добавить счетчик</div></div>
        <?php endif ?>
    </div>
</div>
<?php endif; ?>