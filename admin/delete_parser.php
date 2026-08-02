<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
//echo "<pre>";
//print_r($_POST);
//echo "<pre>";

$id = $_POST['id'];
$query = "DELETE FROM `info_add_train`";
//echo $query."<br>";
mysqli_query($link, $query);



header("Location: index.php?train_parser=true");
mysqli_close($link);
?>