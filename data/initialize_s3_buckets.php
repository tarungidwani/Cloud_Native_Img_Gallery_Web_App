<?php
    /* Paths to imgs that will
     * pre-seated in this
     * gallery web app
     */
    define("IMG_1_raw"     , dirname(__DIR__) . '/web-app/imgs/eartrumpet.png');
    define("IMG_1_finished", dirname(__DIR__) . '/web-app/imgs/eartrumpet-bw.png');
    define("IMG_2_raw"     , dirname(__DIR__) . '/web-app/imgs/Knuth.jpg');
    define("IMG_2_finished", dirname(__DIR__) . '/web-app/imgs/Knuth-bw.jpg');
    define("IMG_3_raw"     , dirname(__DIR__) . '/web-app/imgs/mountain.jpg');
    define("IMG_3_finished", dirname(__DIR__) . '/web-app/imgs/mountain-bw.jpg');

    function setup_prepared_statement($mysql_connection, $jobs_table_name)
    {
        $insert_raw_img_record_stmt = $mysql_connection->prepare("INSERT INTO $jobs_table_name VALUES (?,?,?,?,?,?,?)");

        if(!$insert_raw_img_record_stmt)
        {
            echo "Failed to insert raw img job record (*Prepare failed: " . $mysql_connection->error . "*)";
            $mysql_connection->close();
            exit(1);
        }
        return $insert_raw_img_record_stmt;
    }
