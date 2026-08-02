<?php
require "fire_base/vendor/autoload.php";
require_once '../api/connection.php'; // подключаем скрипт
error_reporting(E_ALL);
ini_set("display_errors", 1);
const DEFAULT_URL = 'https://train-9b5c9.firebaseio.com/';
const DEFAULT_TOKEN = 'fm1GhKwuXophcjJW2kzvxtiDw8JHkJ525FTKvdHT';
const PATH_MESSAGES = '/messages/';
const PATH_CHATS = '/chats';
const UUID_ADMIN = 'vpp9CXouhwMBkn6kYIlmcXVEgrA3';
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

if (isset($_POST['key'])) {

    echo deleteMessage($_POST['key'],$_POST['key_mess'], $_POST['index'],$link);
}




function getUserInfo($uid, $link){
    $query = "SELECT * FROM `users` WHERE `uid`='".$uid."'";
    $name = "";
    $email = "";
    $result = mysqli_query($link, $query);
    if($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $name = $row['name'];
            $email = $row['email'];
        }
    }
    if($name == ""){
        return $email;
    }else{
        return $name;
    }

}

function getMessage($firebase, $key){
    $messages = $firebase->get(PATH_MESSAGES . $key);
    $arrayMessages = json_decode($messages,true);

    if($arrayMessages != null) {
        foreach ($arrayMessages as $message) {
            $result[] = array(
                'message' => $message['message'] ,
                'sender' =>  $message['sender'] ,
                'timestamp' => $message['timestamp'] ,
                'uidMessage' => $message['uidMessage'],
                'uidRoom' =>  $message['uidRoom']
            );
        }
    }
    return $result;
}

function returnMessage($link, $key, $index){
    $result = "";
    $firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

    $message = getMessage($firebase, $key);

    foreach ($message as $mess) {
        $userInfo = getUserInfo($mess['sender'],$link);
        $date = date("H:i:s d.m.Y", $mess['timestamp']/1000);
        if($mess['sender'] == UUID_ADMIN) {
            $result .= "<div class=\"item\"><div class=\"message_user\">" . $userInfo . "</div>
        <div  class=\"message\">" . $mess['message'] . "</div><div class=\"message_time\">" . $date . "</div>
        <div class=\"message_delete\"><button class=\"delete_mess\" name=\"".$key."\" id=\"".$index."\" value=\"" . $mess['uidMessage'] . "\">Удалить</button></div>
        </div>";

        }else{
            $result .= "<div class=\"item\"><div class=\"message_user\">" . $userInfo . "</div>
        <div  class=\"message\">" . $mess['message'] . "</div><div class=\"message_time\">" . $date . "</div></div>";
        }
//                echo $userInfo;
//
//                echo date("H:i:s d.m.Y", $mess['timestamp']/1000);
    }

    return $result;

}

function deleteMessage($key, $key_mess, $index,  $link){
    $firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

    $path = PATH_MESSAGES . $key ."/". $key_mess;
    $firebase->delete($path);

    return returnMessage($link, $key, $index);
}

mysqli_close($link);