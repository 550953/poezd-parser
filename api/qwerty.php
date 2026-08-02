<?php
require_once 'connection.php'; // подключаем скрипт
include '../simple_html_dom.php';


header('Content-type: application/json');
// подключаемся к серверу
$host = '127.0.0.1'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'K2ClMv77SQT3gF3k'; // пароль
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '256M');
function get_web_page( $url )
{
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}


// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//if(isset($_GET['train']))
//saveTrain($link);
//clearComment($link);

goit();
function goit(){
  $url = "https://uz.gov.ua/passengers/timetable/?from_station=22000&to_station=47548%2C23092%2C47543%2C23081%2C47510%2C47636%2C23215%2C36921%2C23203%2C23200&select_time=2&time_from=00&time_to=24&by_route=%D0%9F%D0%BE%D1%88%D1%83%D0%BA";   
        $result = get_web_page($url);
        $err = 0;
        if ($result['errno'] != 0) {
            $result = get_web_page($url);
            $err = 1;
        }

        if ($result['http_code'] != 200) {
            $err = 1;
        }

        $page = $result['content'];

        $html = str_get_html($page);
		print_r($html);

}
function clearComment($link){

	$query = "SELECT * FROM `rx_station_info`";
     echo $query;
    $result = mysqli_query($link, $query);
    
    if($result){
   // echo $query;
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $buff = str_replace('\n', '', $row['buffer']);
            $buff = explode(" | ", $row['buffer']);
      //  print_r($buff);
    		$buff = array_unique($buff);
        //  print_r($buff);
    		$buff = implode(" | ",$buff);
        //  print_r($buff);
    		$go = "UPDATE `rx_station_info` SET `buffer` = '".$buff."' WHERE `rx_station_info`.`id` = '".$row['id']."'";
       // echo $go;
    		mysqli_query($link, $go);
    	}
    }

$query = "SELECT * FROM `rx_station_info`";
   
    $result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
     //$comment = str_replace(' | ', '', $row['comment']);
    // $buffer = str_replace(' | ', '', $row['buffer']);
    		$buff = explode(" | ", $row['buffer']);
    $buff_new = array();
    foreach ($buff as $buffer){
    		 if(strcmp($row['comment'],$buffer) == 0){
           	//	echo $row['comment']."___".$buffer;
           		// if (strlen($buffer) > 0){
           		// $go = "UPDATE `rx_station_info` SET `buffer` = '' WHERE `rx_station_info`.`id` = '".$row['id']."'";
           		// //echo $go;
           		// 	mysqli_query($link, $go);
           		// }
               // 
           }else{
             	if (strlen($buffer) > 0){
             		$buff_new[] = $buffer;
             	}
            }
    
    
    	
    }
    $buff = implode(" | ",$buff_new);
          $go = "UPDATE `rx_station_info` SET `buffer` = '".$buff."' WHERE `rx_station_info`.`id` = '".$row['id']."'";
          //echo $go;
          mysqli_query($link, $go);
    }
    }
}


?>