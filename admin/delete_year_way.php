<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
//echo "<pre>";
//print_r($_POST);
//echo "<pre>";

$id = $_POST['id'];
$query = "DELETE FROM `rx_train_date` WHERE `id_train` = ".$id;
//echo $query."<br>";
mysqli_query($link, $query);

$query = "DELETE FROM `rx_train_way` WHERE `id` = ".$id;
//echo $query."<br>";
$result = mysqli_query($link, $query);

header("Location: index.php?year_way=true");
mysqli_close($link);
?>