<?php
require_once __DIR__ . '/BsLogger.php';
@include_once '/home/provodnik/logs/poezd-diagnostics.php';
if (!function_exists('poezd_diag_log')) { function poezd_diag_log($event, $fields = array()) {} }
if (!function_exists('poezd_diag_hash')) { function poezd_diag_hash($value) { return 'unknown'; } }
if (!function_exists('poezd_diag_request_id')) { function poezd_diag_request_id() { return 'unknown'; } }
if (!function_exists('poezd_diag_platform')) { function poezd_diag_platform($value) { return 'unknown'; } }
ini_set('display_errors', 0);
error_reporting(0);

require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$poezd_diag_id = poezd_diag_request_id();
$poezd_diag_started = microtime(true);
$poezd_diag_ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : 'unknown';
$poezd_diag_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/api/poezd/parse/api/save_user.php';
$_bs_req_start = microtime(true);

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$poezd_diag_action = null;
if (is_array($data)) {
    if (isset($data['email']) && $data['email'] !== null) $poezd_diag_action = 'registration_email';
    elseif (isset($data['huawei']) && $data['huawei'] !== null) $poezd_diag_action = 'registration_huawei';
    elseif (isset($data['saveYandex']) && $data['saveYandex'] !== null) $poezd_diag_action = 'registration_yandex';
    elseif (isset($data['addYandex']) && $data['addYandex'] !== null) $poezd_diag_action = 'link_yandex';
    elseif (isset($data['get_sub_ios_email']) && $data['get_sub_ios_email'] !== null) $poezd_diag_action = 'get_sub_ios_email';
    elseif (isset($data['get_sub_ios']) && $data['get_sub_ios'] !== null) $poezd_diag_action = 'get_sub_ios';
}
$poezd_diag_context = array(
    'request_id' => $poezd_diag_id,
    'action' => $poezd_diag_action,
    'ip' => $poezd_diag_ip,
    'request_uri' => $poezd_diag_uri,
    'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'unknown',
    'platform' => poezd_diag_platform(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''),
    'input_bytes' => strlen((string) $postData),
    'json_error' => json_last_error()
);
if ($poezd_diag_action !== null) {
    poezd_diag_log('save_user.start', $poezd_diag_context);
    register_shutdown_function(function () use ($poezd_diag_id, $poezd_diag_started, $poezd_diag_ip, $poezd_diag_uri) {
        $last = error_get_last();
        $fatalTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING);
        $fatalType = ($last && in_array($last['type'], $fatalTypes, true)) ? $last['type'] : null;
        poezd_diag_log('save_user.finish', array(
            'request_id' => $poezd_diag_id,
            'ip' => $poezd_diag_ip,
            'request_uri' => $poezd_diag_uri,
            'duration_ms' => round((microtime(true) - $poezd_diag_started) * 1000, 2),
            'fatal_type' => $fatalType
        ));
    });
}
if ($poezd_diag_action !== null) poezd_diag_log('save_user.mysql_connected', array_merge($poezd_diag_context, array('db' => 'connected')));


if(isset($data['get_user_yandex']) && $data['get_user_yandex'] != null){
    getUserYandex($data['get_user_yandex'],$link);
}

if(isset($data['isHaveUserYandex']) && $data['isHaveUserYandex'] != null){
    isHaveUserYandex($data['isHaveUserYandex'],$link);
}



function getUserYandex ($uid, $link){
$return_arr = array();
$query = "SELECT * FROM users WHERE `id_yandex`='".$uid."'";
//$query = "SELECT * FROM users";

$fetch = mysqli_query($link,$query);
if($fetch){
    while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['uid'] = $row['uid'];
        $row_array['name'] = $row['name'];
        $row_array['date_create'] = $row['date_create'];
        $row_array['email'] = $row['email'];
    $row_array['email_yandex'] = $row['email_yandex'];
    $row_array['name_yandex'] = $row['name_yandex'];
    $row_array['family_yandex'] = $row['family_yandex'];
    
        array_push($return_arr,$row_array);
    }
}else{
    $return_arr = array("message" => false);
}
echo json_encode($return_arr);
}




function isHaveUserYandex ($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `id_yandex`='".$uid."'";
    
    $fetch = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)){
        $return_arr = array(
        "message" => true,
        "data" => $row
        );
    }else{
        $return_arr = array("message" => false);
    }
    
    echo json_encode($return_arr);


}

if(isset($data['saveYandex']) && isset($data['emailYandex']) && $data['emailYandex'] != null){
 	$query = "SELECT * FROM users WHERE `id_yandex`='".$data['idYandex']."'";
    
    $fetch = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)){
        $return_arr = array(
        "message" => true,
        "data" => $row
        );
    	echo json_encode($return_arr);
    }else{
        saveUserYandex($data['version'], $data['uid'], $data['emailYandex'],$data['idYandex'],$data['date_create'],$data['date_end'], $data['nameYandex'],$data['familyYandex'],$link);
    }


    
}
function saveUserYandex ($version,$uid, $email, $id,$date_create,$date_end, $name, $family, $link){
 if($version == null)
    	$date_end = $date_create;
    $return_arr = array();
$randId = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
	$rand = strtoupper(bin2hex(openssl_random_pseudo_bytes(4)));
	//INSERT INTO `users`(`id`, `uid`, `name`, `email`, `date_create`, `promo`) VALUES  (NULL,'".$uid."','".$name."', '".$email."', '".$date_create."', '".$rand."')
    $query = "INSERT INTO `users`(`id`, `id_yandex`, `name_yandex`, `family_yandex`, `email_yandex`, `date_create`, `promo`, `uid`) VALUES  (NULL,'".$id."','".$name."','".$family."', '".$email."', '".$date_create."', '".$rand."', '".$uid."')";
    $result = mysqli_query($link, $query);
    if($result){
       $id_user = mysqli_insert_id($link);
       saveSub($id_user, $date_create, $date_end, $link);
 
    }else{
        $return_arr = array("message" => $query);
        echo json_encode($return_arr);
    }

}

if(isset($data['addYandex']) && $data['addYandex'] != null){
    addUserYandex($data['addYandex'], $data['emailYandex'],$data['idYandex'], $data['nameYandex'],$data['familyYandex'],$link);
}
function addUserYandex ($uid, $email, $id, $name, $family, $link){

$query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        $id_user = null;
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        }
        
         $return_arr = array();
	$query = "UPDATE `users` SET `id_yandex` = '".$id."', `name_yandex` = '".$name."', `family_yandex` = '".$family."', `email_yandex` = '".$email."' WHERE `id` = ".$id_user;

    $result = mysqli_query($link, $query);
    if($result){
       $return_arr = array("message" => true);
    }else{
        $return_arr = array("message" => false);
    }
        
    }else{
        $return_arr = array("message" => false);
    }
    echo json_encode($return_arr);

 
   

}



if(isset($data['get_user']) && $data['get_user'] != null){
    getUser($data['get_user'],$link);
}

if(isset($data['isHaveUser']) && $data['isHaveUser'] != null){
    isHaveUser($data['isHaveUser'],$link);
}

if(isset($data['isHaveUserApp']) && $data['isHaveUserApp'] != null){
    isHaveUser($data['isHaveUserApp'],$link);
}
if(isset($data['email']) && $data['email'] != null){
    poezd_diag_log('save_user.registration_received', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'provider' => 'email', 'uid_hash' => poezd_diag_hash(isset($data['uid']) ? $data['uid'] : ''), 'email_hash' => poezd_diag_hash($data['email'])));
    saveUser($data['version'] ?? null, $data['email'],$data['uid'],$data['date_create'],$data['date_end'], $data['name'],$link);
    BsLogger::request('/api/poezd/parse/api/save_user.php',200,round((microtime(true)-$_bs_req_start)*1000,2),$data['uid']??null);
}

if(isset($data['huawei']) && $data['huawei'] != null){
    poezd_diag_log('save_user.registration_received', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'provider' => 'huawei', 'uid_hash' => poezd_diag_hash(isset($data['uid']) ? $data['uid'] : ''), 'email_hash' => poezd_diag_hash(isset($data['email']) ? $data['email'] : '')));
    saveUser($data['version'] ?? null, $data['email'],$data['uid'],$data['date_create'],$data['date_end'], $data['name'],$link);
    BsLogger::request('/api/poezd/parse/api/save_user.php',200,round((microtime(true)-$_bs_req_start)*1000,2),$data['uid']??null);
}

if(isset($data['get_sub']) &&  $data['get_sub'] != null){
    getSub($data['get_sub'],$link);
}
if(isset($data['get_sub_ios']) && $data['get_sub_ios'] != null){
    getSubIos($data['get_sub_ios'],$link);
    BsLogger::request('/api/poezd/parse/api/save_user.php',200,round((microtime(true)-$_bs_req_start)*1000,2),$data['get_sub_ios']);
}
if(isset($data['get_sub_ios_email']) && $data['get_sub_ios_email'] != null){
    getSubIosMail($data['get_sub_ios_email'],$link);
    BsLogger::request('/api/poezd/parse/api/save_user.php',200,round((microtime(true)-$_bs_req_start)*1000,2));
}



if(isset($data['set_sub']) && $data['set_sub'] != null && $data['time'] != null){
    setSub($data['set_sub'], $data['time'], $link);
}
if(isset($data['set_sub_join']) && $data['set_sub_join'] != null && $data['time'] != null){
    setSubJoin($data['set_sub_join'], $data['time'], $link);
}

if(isset($data['save_invite']) && $data['save_invite'] != null){
    saveInvite($data['save_invite'], $data['time'], $link);
}
if(isset($data['delete_invite']) && $data['delete_invite'] != null){
    deleteInvite($data['delete_invite'],$link);
}
if(isset($data['add_ref']) && $data['add_ref'] != null){
    addReference($data['add_ref'],$data['time'],$link);
}
if(isset($data['get_invite']) && $data['get_invite'] != null){
    getInvite($data['get_invite'], $data['time_add'], $link);
}
if(isset($data['set_name']) && $data['set_name'] != null){
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

function setSubJoin($uid, $time, $link){
   
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
        getSub($uid, $link);
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
        $id_user = null;
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
            $return_arr = array("message" => $id_user);
        }
        
        
    }else{
        $return_arr = array("message" => false);
    }
    echo json_encode($return_arr);
}
function getSubIos($uid, $link){
global $poezd_diag_id;
$poezd_diag_query_started = microtime(true);
if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_user_query.start', array('request_id' => $poezd_diag_id, 'uid_hash' => poezd_diag_hash($uid)));
    // FIX: always return {"end": N} so iOS spinner stops correctly
    $id_user = null;
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        }
    }
    if($id_user === null){
        if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_user_query.finish', array('request_id' => $poezd_diag_id, 'found' => false, 'duration_ms' => round((microtime(true) - $poezd_diag_query_started) * 1000, 2)));
        echo json_encode(array("end" => 0));
        return;
    }
    $query_sub = "SELECT * FROM subscription WHERE `id_user`=".$id_user;
    $fetch_sub = mysqli_query($link,$query_sub);
    $return_arr = array("end" => 0);
    if($fetch_sub){
        $found = false;
        while ($row = mysqli_fetch_array($fetch_sub, MYSQLI_ASSOC)) {
            $end = intdiv($row['date_end'],1000);
            $return_arr = array("end" => $end);
            $found = true;
        }
        if(!$found){
            $return_arr = array("end" => 0);
        }
    }
    if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_user_query.finish', array('request_id' => $poezd_diag_id, 'duration_ms' => round((microtime(true) - $poezd_diag_query_started) * 1000, 2)));
    $now_ts = time();
    $end_ts = isset($return_arr['end']) ? $return_arr['end'] : 0;
    BsLogger::event('info','save_user',($end_ts > $now_ts ? 'subscription_active' : 'subscription_expired'),['uid_hash'=>substr(md5($uid),0,8),'end'=>$end_ts]);
    echo json_encode($return_arr);
}

function getSubIosMail($uid, $link){
global $poezd_diag_id;
$poezd_diag_query_started = microtime(true);
if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_email_query.start', array('request_id' => $poezd_diag_id, 'email_hash' => poezd_diag_hash($uid)));
    // FIX: always return {"end": N} so iOS spinner stops correctly
    $id_user = null;
    $query = "SELECT `id` FROM users WHERE `email`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        }
    }
    if($id_user === null){
        if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_email_query.finish', array('request_id' => $poezd_diag_id, 'found' => false, 'duration_ms' => round((microtime(true) - $poezd_diag_query_started) * 1000, 2)));
        echo json_encode(array("end" => 0));
        return;
    }
    $query_sub = "SELECT `date_start`,`date_end` FROM subscription WHERE `id_user`=".$id_user;
    $fetch_sub = mysqli_query($link,$query_sub);
    $return_arr = array("end" => 0);
    if($fetch_sub){
        $found = false;
        while ($row = mysqli_fetch_array($fetch_sub, MYSQLI_ASSOC)) {
            $end = intdiv($row['date_end'],1000);
            $return_arr = array("end" => $end);
            $found = true;
        }
        if(!$found){
            $return_arr = array("end" => 0);
        }
    }
    if (isset($poezd_diag_id)) poezd_diag_log('save_user.subscription_email_query.finish', array('request_id' => $poezd_diag_id, 'duration_ms' => round((microtime(true) - $poezd_diag_query_started) * 1000, 2)));
    echo json_encode($return_arr);
}


function getSubId($uid, $link){
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    $fetch = mysqli_query($link,$query);
    if($fetch){
        $id_user = null;
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
    global $poezd_diag_id, $poezd_diag_ip;
    $query = "INSERT INTO subscription (`id_user`, `date_start`, `date_end`) VALUES (" . (int) $id_user . ",'" . (int) $date_start . "','" . (int) $date_end . "')";
    $result = mysqli_query($link, $query);
    if($result){
       poezd_diag_log('save_user.subscription_inserted', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'user_id' => (int) $id_user));
       echo json_encode(array('message' => true));
    }else{
       poezd_diag_log('save_user.subscription_insert_failed', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'user_id' => (int) $id_user, 'mysql_errno' => mysqli_errno($link), 'mysql_error' => mysqli_error($link)));
       echo json_encode(array('message' => false, 'error' => 'subscription_insert_failed'));
    }
}

function saveUser ($version, $email, $uid,$date_create,$date_end, $name, $link){
    global $poezd_diag_id, $poezd_diag_ip;
    if($version == null) $date_end = $date_create;
    $uidHash = poezd_diag_hash($uid);
    $emailHash = poezd_diag_hash($email);
    poezd_diag_log('save_user.user_insert_start', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'uid_hash' => $uidHash, 'email_hash' => $emailHash));
    $query = "INSERT INTO `users`(`uid`, `name`, `email`, `date_create`, `promo`) VALUES ('" . mysqli_real_escape_string($link, $uid) . "','" . mysqli_real_escape_string($link, $name) . "','" . mysqli_real_escape_string($link, $email) . "','" . (int) $date_create . "','" . strtoupper(bin2hex(openssl_random_pseudo_bytes(4))) . "')";
    $result = mysqli_query($link, $query);
    if($result){
       $id_user = mysqli_insert_id($link);
       poezd_diag_log('save_user.user_inserted', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'uid_hash' => $uidHash, 'user_id' => $id_user));
       saveSub($id_user, $date_create, $date_end, $link);
    }else{
        poezd_diag_log('save_user.user_insert_failed', array('request_id' => $poezd_diag_id, 'ip' => $poezd_diag_ip, 'uid_hash' => $uidHash, 'mysql_errno' => mysqli_errno($link), 'mysql_error' => mysqli_error($link)));
        echo json_encode(array('message' => false, 'error' => 'user_insert_failed'));
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
    BsLogger::event('info','save_user','user_login',['uid_hash'=>substr(md5($uid),0,8)]);
    $return_arr = array();
    $query = "SELECT * FROM users WHERE `uid`='".$uid."'";
    
    $fetch = mysqli_query($link,$query);
    if($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)){
        $return_arr = array(
        "message" => true,
        "data" => $row
        );
    }else{
        $return_arr = array("message" => false);
    }
    
    echo json_encode($return_arr);


}
// закрываем подключение
mysqli_close($link);
?>