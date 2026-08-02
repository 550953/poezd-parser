<?php

require_once 'connection.php'; // подключаем скрипт

// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
if($data['get_user'] != null){
    getUser($data['get_user'],$link);
}

if($data['isHaveUser'] != null){
    isHaveUser($data['isHaveUser'],$link);
}
if($data['email'] != null){
    saveUser($data['version'], $data['email'],$data['uid'],$data['date_create'],$data['date_end'], $data['name'],$link);
}
if($data['get_sub'] != null){
    getSub($data['get_sub'],$link);
}
if($data['set_sub'] != null && $data['time'] != null){
    setSub($data['set_sub'], $data['time'], $link);
}
if($data['save_invite'] != null){
    saveInvite($data['save_invite'], $data['time'], $link);
}
if($data['delete_invite'] != null){
    deleteInvite($data['delete_invite'],$link);
}
if($data['add_ref'] != null){
    addReference($data['add_ref'],$data['time'],$link);
}
if($data['get_invite'] != null){
    getInvite($data['get_invite'], $data['time_add'], $link);
}
if($data['set_name'] != null){
    setName($data['uid'], $data['set_name'], $link);
}
//setName("8mCu7F1ftjNP01ujD68H1fB2SVt2","cxcxcxcxc",$link);

function setName($uid, $name, $link){
   
    $return_arr = array();
    
    $query = "UPDATE `users` SET `name` = '".$name."' WHERE `uid` = '".$uid."'";

    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
     echo json_encode($return_arr);
}



function getInvite ($uid, $time_add, $link){
    $return_arr = array();
    $query = "SELECT * FROM invitation WHERE `uid` = '".$uid."'";
    $result = mysqli_query($link, $query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $time_create = $row['date_reg'];
            addReference($uid, $time_create, $link);
            deleteInvite ($uid, $link);
            setSub($uid, $time_add, $link);
            
        }
    
        if($row == null){
            $return_arr = array("message" => false);
        }else{
            $return_arr = array("message" => true);
        }
        
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
}

function saveInvite ($uid, $time, $link){
    $return_arr = array();
    $query = "INSERT INTO invitation VALUES (NULL,'".$uid."',$time)";
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
}
function addReference($uid, $time, $link){
    $userId = getUserId ($uid, $link);
    $return_arr = array();
    $query = "INSERT INTO referense VALUES (NULL, $userId,'".$time."' )";
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
    
}
function deleteInvite ($uid, $link){
    $return_arr = array();
    $query = "DELETE FROM `invitation` WHERE `invitation`.`uid` = '".$uid."'";
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
}
//saveUser("baksys@ya.ru",12155522222,323332333333,121545212,"Anatoly",$link);
//setSub("WRWr6PbDoTR0y5dppin8j1zWTUC3",234,$link);
//isHaveUser("8mCu7F1ftjNP01ujD68H1fB2SVt2", $link);
function setSub($uid, $time, $link){
   
    $return_arr = array();
    $array = getSubId($uid, $link);
  
    $id = $array[0];
    $milliseconds = round(microtime(true) * 1000);

    if($array[1] > $milliseconds){
        $times = $array[1] + $time;
    }else{
        $times = $milliseconds + $time;
    }
   // echo $times." ".$array[1]." ".$time;

    
    $query = "UPDATE `subscription` SET `date_end` = ".$times." WHERE `id` = ".$id;
    //echo $query;
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
     echo json_encode($return_arr);
}

function getSub($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        }
        $query_sub = "SELECT * FROM subscription WHERE `id_user`=".$id_user;
        $fetch_sub = mysqli_query($link,$query_sub);
        if($fetch_sub){
             while ($row = mysqli_fetch_array($fetch_sub, MYSQLI_ASSOC)) {
                $row_array['date_start'] = $row['date_start'];
                $row_array['date_end'] = $row['date_end'];
                array_push($return_arr,$row_array);
             }
            
        }else{
            $return_arr = array("message" => false);
        }
        
        
    }else{
        $return_arr = array("message" => false);
    }
    echo json_encode($return_arr);
}


function getSubId($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        }
        $query_sub = "SELECT * FROM subscription WHERE `id_user`=".$id_user;
        $fetch_sub = mysqli_query($link,$query_sub);
        if($fetch_sub){
             while ($row = mysqli_fetch_array($fetch_sub, MYSQLI_ASSOC)) {
                $id_sub = $row['id'];
                $date_end = $row['date_end'];
             }
            
        }else{
            $return_arr = array("message" => false);
        }
        
        
    }else{
        $return_arr = array("message" => false);
    }
    $array [] = $id_sub;
    $array [] = $date_end;
    return $array;
}




function saveSub ($id_user, $date_start, $date_end, $link){
   
    $return_arr = array();
    $query = "INSERT INTO subscription VALUES (NULL,'".$id_user."','".$date_start."', '".$date_end."')";
    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
echo json_encode($return_arr);
}

function saveUser ($version, $email, $uid,$date_create,$date_end, $name, $link){
 if($version == null)
    	$date_end = $date_start;
    $return_arr = array();
    $query = "INSERT INTO users VALUES (NULL,'".$uid."','".$name."', '".$email."', '".$date_create."')";
    $result = mysqli_query($link, $query);
    if($result){
       $id_user = mysqli_insert_id($link);
       saveSub($id_user, $date_create, $date_end, $link);
 
    }else{
        $return_arr = array("message" => false);
        echo json_encode($return_arr);
    }

}

function getUser ($uid, $link){
$return_arr = array();
$query = "SELECT * FROM users WHERE `uid`='".$uid."'";
//$query = "SELECT * FROM users";

$fetch = mysqli_query($link,$query);
if($fetch){
    while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['uid'] = $row['uid'];
        $row_array['name'] = $row['name'];
        $row_array['date_create'] = $row['date_create'];
        $row_array['email'] = $row['email'];
    
        array_push($return_arr,$row_array);
    }
}else{
    $return_arr = array("message" => false);
}
echo json_encode($return_arr);
}


function getUserId ($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    
    $fetch = mysqli_query($link,$query);
    if($fetch){
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $user_id = $row['id'];
        }
    }else{
        $user_id = 0;
    }
return $user_id;
}


function isHaveUser ($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    
    $fetch = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)){
        $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
    
    echo json_encode($return_arr);


}
// закрываем подключение
mysqli_close($link);
?>