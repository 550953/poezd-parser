<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

echo "<pre>";
print_r($_POST);
echo "<pre>";

$number = $_POST['number'];
$date_start = $_POST['date_start'];



$query = "SELECT * FROM `mn_way_info` WHERE `train_name` = '".$number."' AND `date_start` = '".$date_start."'";
$result = mysqli_query($link, $query);
if($result) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $query = "DELETE FROM `mn_pass_info` WHERE `id_info` = " . $row['id'];
        mysqli_query($link, $query);

    }
}

$query = "DELETE FROM `mn_way_info` WHERE `train_name` = '".$number."' AND `date_start` = '".$date_start."'";
mysqli_query($link, $query);



header("Location: index.php?monitoring=true");
mysqli_close($link);
?>