<?php
    define('DB_SERVER', 'localhost:3306');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'project1');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    if (mysqli_connect_errno())
    trigger_error("Unable to connect to MySQLi database.");
    $db->set_charset('utf8');
    date_default_timezone_set('Europe/Dublin');
?>