<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);



if (isset($_POST['count']) && isset($_POST['uid'])) {

    updateInfoChat($_POST['uid'], $_POST['count'],$link);
}

function updateInfoChat($uid, $count, $link){

    echo "COUNT = ".$count;

    $query = "UPDATE `chats_info` SET `count` = ".$count." WHERE `uid` = '".$uid."'";
    mysqli_query($link, $query);

}

mysqli_close($link);