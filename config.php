<?php
    define('BASE_URL', 'http://native_php.test');
    $server = 'localhost';
    $username = 'root';
    $password = 'root1234';
    $db = 'finalTiny';

    $link = mysqli_connect($server,$username,$password,$db);

    if(!$link){
        die('Database connection error '.mysqli_connect_error());
    }
?>