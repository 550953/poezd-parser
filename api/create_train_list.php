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
$train = $data['train'];
// if($train != null){
    saveTrain($train, $link);
// echo "ddd";
// }



function saveTrain ($train, $link){
    $return_arr = array();


    $query = "SELECT * FROM `trains_list` ORDER BY `name_train` ASC";
    $result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;
    }
    
  

    if($array != null){
        $return_arr = array(  // Формируем массив
            "result" => $array
            
        );
     $info = json_encode($return_arr);
    $file = fopen('name_train.json','w+') or die("File not found");
    fwrite($file, $info);
    fclose($file);
	$return_arr = array("message" => true);
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