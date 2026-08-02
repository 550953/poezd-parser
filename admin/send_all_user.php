<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");


$message = "Сообщение:\n";
$message .= $_POST['message'];
//83,22,42
$email_from = "send@poezd.androiddev.xyz"; // от кого
$sub = "Оповещение от администрации приложения \"Проводник вагона\""; // тема письма
$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";
$query = "SELECT * FROM `users`";
$result = mysqli_query($link, $query);

if($result) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $email = $row['email'];
        mail($email, $subject, $message, "From: $email_from\r\nReply-To: $email_from\r\nReturn-Path: $email_from\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit");
    }

}

mysqli_close($link);
?>