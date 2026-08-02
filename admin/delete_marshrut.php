<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

    $id = $_POST['id'];
    $query = "DELETE FROM `rx_train_station` WHERE `id_train` = ".$id;
    mysqli_query($link, $query);

 	$query = "DELETE FROM `rx_train_route` WHERE `id_train` = ".$id;
    mysqli_query($link, $query);

    $query = "DELETE FROM `rx_train_info` WHERE `id` = ".$id;
    //echo $query."<br>";
    $result = mysqli_query($link, $query);

header("Location: index.php?marshrut=true");
mysqli_close($link);
?>