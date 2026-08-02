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


$day = $_POST['day'] * 24 * 60 * 60 * 1000;

echo $day;

$query = "SELECT * FROM `users`";
//$query = "SELECT * FROM `tech_ways` WHERE `name` = '".$train."'  AND `start` = '".$start."' AND `startTime` = '".$startTime."' AND `end` = '".$end."' AND `endTime` = '".$endTime."'";
$result = mysqli_query($link, $query);
$count = mysqli_num_rows($result);
$id_user = 0;
if($count > 0){
    if ($result) {
       while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
            $find = "SELECT * FROM `subscription` WHERE `id_user`=".$id_user;
            $result_find = mysqli_query($link, $find);
            if ($result_find) {
                while ($row_find = mysqli_fetch_array($result_find, MYSQLI_ASSOC)) {
                    $id_sub = $row_find['id'];
                    $id_sub_s = $row_find['date_start'];
                    $id_sub_e = $row_find['date_end'];
                	

                }
            }
            setSub($id_sub_e,$day,$id_sub,$link);
        }
    }
}


function setSub($date_end, $time, $id, $link){
    $now = microtime(true) * 1000;
    if($date_end > $now){
    	$times = $date_end + $time;
    }else{
    	$times = $now + $time;
    }
    // echo $times." ".$array[1]." ".$time;


    $query = "UPDATE `subscription` SET `date_end` = ".$times." WHERE `id` = ".$id;
    //echo $query;
    $result = mysqli_query($link, $query);
    echo $result;

}

mysqli_close($link);
?>