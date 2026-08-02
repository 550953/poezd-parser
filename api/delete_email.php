<?php
require_once 'connection.php'; // подключаем скрипт

// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
if($data['id_email'] != null){
    deleteEmail($data['id_email'],$link);
}
function deleteEmail ($id, $link){
    $return_arr = array();
    $query = "DELETE FROM `email` WHERE `email`.`id` = '".$id."'";
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
}
// закрываем подключение
mysqli_close($link);
?>