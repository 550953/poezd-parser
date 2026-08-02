<?php

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);




$messege = "Сообщение:\n";
$messege .= $data['text'];
$email_to = $data['email'];

//,sam4488831@mail.ru
$email_from = "info@poezd.androiddev.xyz"; // от кого
$to = "baksys@ya.ru"; // кому
$sub = "Ответ с обратной связи приложения \"Проводник вагона\""; // тема письма
$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";

if(mail($email_to, $subject, $messege, "From: $email_from\r\nReply-To: $email_from\r\nReturn-Path: $email_from\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit")){
    $all_mass = array("messege" => true);
}else{
    $all_mass = array("messege" => false);
}

        echo json_encode($all_mass);


?>