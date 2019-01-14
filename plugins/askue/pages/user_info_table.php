<div class="user-info-table">
    <div class="user-info-container">

        <div class="user-info-title-block">
            <div class="user-info-title">Пользователь</div>
        </div>

        <div class="user-info-wrap">
            <div class="user-info-content">
                <div class="user-icon-block">
                    <div class="user-icon-container"><div class="user-icon"></div></div>
                    <div class="user-login-title"><?=$customer->getLogin();?></div>
                </div>

                <div class="user-info-right">
                    <div class="user-info-row">ФИО: <?=$customer->getFIO();?></div>
                    <div class="user-info-row">Группа: <?=$customer_group->getName();?></div>
                </div>
            </div>
        </div>

    </div>

    <div class="user-info-spacer"></div>

    <?php include(ASKUE_PLUGIN_DIR."pages/statistics_block.php"); ?>

</div>
