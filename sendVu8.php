<?php


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$messege = "Бланк ВУ-8:\n\n";
$messege .= $data['message'];
$mail = $data['mail'];

$email = "send@poezd.androiddev.xyz"; // от кого
$to = $mail; // кому
$sub = "Бланк ВУ-8 с приложения \"Проводник вагона\""; // тема письма
$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";



if(mail($to, $subject, $messege, "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit")){
    $all_mass = array("messege" => true);
}else{
    $all_mass = array("messege" => false);
}

echo json_encode($all_mass);
?>