<?php
    //login to the module3 database
    //wustl_inst has and only has select, insrert, update, delete privileges on the module3 database.
    $mysqli = new mysqli('localhost', 'wustl_inst', 'wustl_pass', 'calendar');

    if($mysqli->connect_errno) {
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit;
    }
?>