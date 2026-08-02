<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host_book, $user_book, $password_book, $database_book) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if(isset($data['getBook']))
    getBook($link);

function getBook($link){
    $query = "SELECT * FROM `previe_book`";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array[] = $row;
        }
        $arr = getVersionApp($link);
        $answer_server = array(
            'message' => true,
            'result' => $array,
        	'url' => $arr['url'],
        	'url_app' => $arr['url_app']
        );
    }else{
         $answer_server = array(
            'message' => false
        );
    }

    echo json_encode($answer_server);
}

if(isset($data['getUrl']))
    getUrl($link);

function getUrl($link){
    $query = "SELECT * FROM `url_parser`";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array[] = $row;
        }
        $answer_server = array(
            'message' => true,
            'result' => $array
        );
    }else{
         $answer_server = array(
            'message' => false
        );
    }

    echo json_encode($answer_server);
}

if(isset($data['getVersion']))
    getVersion($link);

function getVersion($link){
    $array = "flip";
    $query = "SELECT * FROM `url_parser` WHERE `id` = 1";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array = $row['url'];
            
        }
        $answer_server = array(
            'message' => true,
            'result' => $array
        );
    }else{
         $answer_server = array(
            'message' => false
        );
    }
    return $array;
    // echo json_encode($answer_server);
}

function getVersionApp($link){
    $array = array();
    $query = "SELECT * FROM `url_parser` WHERE `id` = 1";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array['url'] = $row['url'];
            $array['url_app'] = $row['url_app'];
            
        }
        $answer_server = array(
            'message' => true,
            'result' => $array
        );
    }else{
         $answer_server = array(
            'message' => false
        );
    }
    return $array;
    // echo json_encode($answer_server);
}



if(isset($data['setUser'])){
    $uid = $data['uid'];
    setUser($link, $uid);
}

function setUser($link, $uid){
    $query = "SELECT * FROM `statistic` WHERE `uuid` = '".$uid."'";
    $result = mysqli_query($link,$query);
    $count = mysqli_num_rows($result);
    if($result && $count == 0){
        $query = "INSERT INTO `statistic` (`id`, `uuid`, `loadCount`, `dateCreate`) VALUES (NULL,'".$uid."',0 , NOW())";
        $result = mysqli_query($link,$query);
    }
}


if(isset($data['addDown'])){
    $uid = $data['uid'];
    addDown($link, $uid);
}

function addDown($link, $uid){
    $query = "SELECT * FROM `statistic` WHERE `uuid` = '".$uid."'";
    $result = mysqli_query($link,$query);
    $count = mysqli_num_rows($result);
    if($result && $count > 0){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
            $count = $row['loadCount'];
        }
        $count = $count + 1;
        $query = "UPDATE `statistic` SET `loadCount`= '".$count."'  WHERE id = ".$id;
        $result = mysqli_query($link,$query);
    }
}

mysqli_close($link);
?>