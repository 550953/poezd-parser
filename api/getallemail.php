<?php
require_once 'connection.php'; // подключаем скрипт

// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

getUser($link);



function getUser ($link){
$return_arr = array();
$query = "SELECT * FROM email ORDER BY `id` DESC";
$fetch = mysqli_query($link,$query);
if($fetch){
    while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['name'] = $row['name'];
        $row_array['email'] = $row['email'];
        $row_array['message'] = $row['message'];
        $row_array['vagon'] = $row['vagon'];
        $row_array['train'] = $row['train'];
        $row_array['date'] = $row['date'];

    
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