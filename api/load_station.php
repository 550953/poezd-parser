<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$train = $data['station'];
if($train != null){
    saveTrain($train, $link);
}



function saveTrain ($train, $link){
    $return_arr = array();


    $query = "SELECT * FROM `express_stations` ORDER BY `name` ASC";
    $result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;
    }
    
  

    if($array != null){
        $return_arr = array(  // Формируем массив
            "result" => $array,
            'message' => 'true'
        );

    }else{
        $return_arr = array("message" => false);
    }
}else{
   
    $return_arr = array("message" => false);
}


echo json_encode($return_arr);
}
// закрываем подключение
mysqli_close($link);
?>