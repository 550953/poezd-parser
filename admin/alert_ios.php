<?php

require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

mysqli_set_charset($link, "utf8");


if (isset($_POST['alert'])) {
    
      save_alert($_POST['alert'],$_POST['on_off'], $link);
}


function save_alert($text,$on_off, $link){
    
 
    $query = "UPDATE `message_ios` SET `text` = '".$text."', `on_off`='".$on_off."'";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    
}

?>