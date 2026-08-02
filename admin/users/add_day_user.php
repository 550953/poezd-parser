<?php
/**
 * Created by PhpStorm.
 * User: anatolijmakarenko
 * Date: 13.03.2018
 * Time: 20:24
 */
require_once '../../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$id = $_POST['id'];
$day = $_POST['day'] * 24 * 60 * 60 * 1000;
$date_end =  $_POST['date_end'];
setSub($date_end, $day, $id, $link);
function setSub($date_end, $time, $id, $link){
    $now = microtime(true) * 1000;
    if($date_end > $now){
    	$times = $date_end + $time;
    }else{
    	$times = $now + $time;
    }


    $query = "UPDATE `subscription` SET `date_end` = ".$times." WHERE `id` = ".$id;
    //echo $query;
    $result = mysqli_query($link, $query);
    echo $result;

}

mysqli_close($link);
?>