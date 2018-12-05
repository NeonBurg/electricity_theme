<script type="text/javascript">
    jQuery("li.current-page-ancestor").addClass('current-menu-item');
</script>

<?php   include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_head.php");
        require_once ($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/askue/models/DataController.php");?>

    <?php
        echo "Meters management content here <br><br>";
        global $wpdb;
        $dataController = new DataController($wpdb);
        $meter_types = $dataController->selectMeterTypes();

        //$meter_types = array();

        if(count($meter_types) != 0) {
            echo " id | type <br>";
            foreach ($meter_types as $meter_type) {
                echo $meter_type->getId() . " | " . $meter_type->getType() . "<br>";
            }
        }
        else {
            echo "Empty meter_types | err = " . $dataController->getErrorMsg()[0];
        }
    ?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/user_room/parts/content_footer.php");?>
