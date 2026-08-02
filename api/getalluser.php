<?php
header('Content-type: application/json');
require_once 'connection.php'; // подключаем скрипт

// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$limit1 = $data['limit1'];
$limit2 = $data['limit2'];


// $limit1 = $_GET['limit1'];
// $limit2 = $_GET['limit2'];
//echo $limit1." - ".$limit2;
// echo json_encode($limit1." - ".$limit2);
if($limit1 != null && $limit2 != null){

}
getUser($link, $limit1, $limit2);



function getUser ($link, $limit1, $limit2){
$return_arr = array();
$query = "SELECT * FROM users LEFT JOIN subscription ON users.id = subscription.id_user LIMIT ".$limit1.",".$limit2;
//echo $query;
$fetch = mysqli_query($link,$query);
if($fetch){
    while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
        $row_array['uid'] = $row['uid'];
        $row_array['name'] = $row['name'];
        $row_array['date_create'] = $row['date_create'];
        $row_array['email'] = $row['email'];
        $row_array['date_start'] = $row['date_start'];
        $row_array['date_end'] = $row['date_end'];
    
        array_push($return_arr,$row_array);
    }
}else{
    $return_arr = array("message" => false);
}



echo json_encode($return_arr);
}
// закрываем подключение
mysqli_close($link);
?>