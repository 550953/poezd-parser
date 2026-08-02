<?php

require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

mysqli_set_charset($link, "utf8");


if (isset($_POST['api'])) {
    
      save_api($_POST['api'], $link);
}

if (isset($_POST['pay'])) {
    
      save_pay($_POST['pay'],$_POST['pay_text'], $link);
}
function save_api($text, $link){
    
 
    $query = "UPDATE `settings` SET `value` = '".$text."' WHERE `settings`.`id` = 1";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    
}

function save_pay($text, $pay_text, $link){

    $query = "UPDATE `settings` SET `value` = '".$text."' ,`period` = '".$pay_text."' WHERE `settings`.`id` = 2";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    
}



?>