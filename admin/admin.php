<?php


require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");


$query = "DELETE FROM `test` WHERE `id` = 1";
mysqli_query($link, $query);
header("Location: index.php?marshrut=true");
mysqli_close($link);
?>