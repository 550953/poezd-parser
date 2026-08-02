<?php

require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

mysqli_set_charset($link, "utf8");


if (isset($_POST['alert'])) {
    
      save_alert($_POST['alert'], $link);
}


function save_alert($text, $link){
    
 
    $query = "UPDATE `message` SET `text` = '".$text."'";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    
}

?>