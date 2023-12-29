<?php
session_start();
include "../authentication.php";
include "../config.php";

$id = $_GET['id'];

if(isset($_SESSION['id'])){
    $sql = "DELETE FROM category WHERE id='$id'";
    $query = mysqli_query($link,$sql);
    if($query){
        $log = getHostByName($_SERVER['HTTP_HOST']).' - '.date("F j, Y, g:i a").PHP_EOL.
            "Record deleted_".time().PHP_EOL.
            "---------------------------------------".PHP_EOL;
        file_put_contents('../logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $_SESSION['warning'] = "One record deleted successfully";
        header('location:category_list.php');
    }else{
        $_SESSION['error'] = "Something is wrong, Record not deleted";
        header('location:category_list.php');
    }
}else{
    $log = getHostByName($_SERVER['HTTP_HOST']).' - '.date("F j, Y, g:i a").PHP_EOL.
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $log = "User".rand().'_'.time().": ".getHostByName($_SERVER['HTTP_HOST']).' - '.date("F j, Y, g:i a").PHP_EOL.
        "Page trying to access: ".$url.PHP_EOL.
        "---------------------------------------".PHP_EOL;
    file_put_contents('logs/unauth_log_'.date("j-n-Y").'.log', $log, FILE_APPEND);
}
?>