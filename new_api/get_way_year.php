<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
include '../simple_html_dom.php';

require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

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










if(isset($_GET['tr'])){
	echo json_encode(ParseTrainInfo($_GET['tr']));
}

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
//
// $trains = $_GET['trains'];
// $full_name = $_GET['full_name'];
if(isset($data['trains'])){
	$trains = $data['trains'];
}
if(isset($data['full_name'])){
	$full_name= $data['full_name'];
}
if(isset($data['new_name'])){
	$new_name = $data['new_name'];
}
//$new_name = $_GET['new_name'];
if(isset($new_name)){
	getTrainDate($new_name, $link);
}


 function getTrainDate($number, $link){
	$query = "SELECT * FROM `year_train` WHERE `number` = '".$number."'";
	$result = mysqli_query($link, $query);
	$count_result= mysqli_num_rows($result);
	$res = null;
	if($count_result > 0){
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {	
        	
        	$resu = $row['text'];
        $res = unserialize($resu); 
        
        //$res = str_replace('\"', '"', $res);
        //	$res = stripslashes($res)
       echo $res;
        }
    }else{
    	$res =array(  
                    "messege" => false
                 );
    }
	echo json_encode($res, JSON_FORCE_OBJECT);
 }



if(isset($trains) &&  $trains != null && $full_name != null) {

    $dateNow = date("Y-m-d H:i:s");

    $query = "SELECT * FROM `rx_train_way` WHERE `number`='" . $trains . "'";

    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);


    if ($count > 0) {
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $date = $row['date_create'];
            }
        }
        $result = strtotime($dateNow) - strtotime($date);


        if ($result > 86400 * 4) {
            $result_arr = ParseTrainInfo($trains);
            if(count($result_arr) > 0) {
                
                $all_mass = array(  // Формируем массив
                    "dates" => $result_arr
                 );
                echo json_encode($all_mass);

                if (!mysqli_ping($link))
                    $link = mysqli_connect($host, $user, $password, $database);
                SaveDateTrainInfo($result_arr, $trains, $full_name, $link);
            }else{
                $all_mass = array(  // Формируем массив
                    'messege' => 'false'
                );
                echo json_encode($all_mass, JSON_FORCE_OBJECT);
            }
        } else {
            echo json_encode(getFromBase($trains, $link));
        }
    } else {
        $result_arr = ParseTrainInfo($trains);
        if(count($result_arr) > 0) {
            $all_mass = array(  // Формируем массив
                    "dates" => $result_arr
                 );
            echo json_encode($all_mass);
            if (!mysqli_ping($link))
                $link = mysqli_connect($host, $user, $password, $database);
             SaveDateTrainInfo($result_arr, $trains, $full_name, $link);
        }else{
            $all_mass = array(  // Формируем массив
                'messege' => 'false'
            );
            echo json_encode($all_mass, JSON_FORCE_OBJECT);
        }
    }

}



function SaveDateTrainInfo($dateArray, $trains, $fullTrain, $link){


    $query = "SELECT * FROM `rx_train_way` WHERE `number`='".$trains."'";
    $result = mysqli_query($link,$query);
    if($result){
        $id_train = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }
        $query = "DELETE FROM `rx_train_date` WHERE `id_train` = ".$id_train;
        mysqli_query($link, $query);


        $query = "DELETE FROM `rx_train_way` WHERE `id` = '".$id_train;
        mysqli_query($link, $query);
    }



    $query = "INSERT INTO `rx_train_way`(`number`, `full_name`, `date_create`) VALUES ('".$trains."','".$fullTrain."',NOW())";
    mysqli_query($link, $query);
    $id_train = mysqli_insert_id($link);

    foreach ($dateArray as $d){
        $query = "INSERT INTO `rx_train_date`(`id`, `id_train`, `date`, `value`) VALUES (null, '".$id_train."', '".$d['date']."', '".$d['value']."')";
        mysqli_query($link, $query);
    }




}


function getFromBase($trains, $link)
{

    $query = "SELECT * FROM `rx_train_way` WHERE `number`='" . $trains . "'";
    $result = mysqli_query($link, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }
    }


    $query = "SELECT * FROM `rx_train_date` WHERE `id_train`=" . $id_train;
    $result = mysqli_query($link, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_date[] = array(
                "date" => $row['date'],
                "value" => $row['value']
            );
        }
    }


    $all_mass = array(  // Формируем массив
        "dates" => $result_date
    );




    return $all_mass;
}


function ParseTrainInfo($trains)
{
    $trains = urlencode($trains);
    
    if($trains != null){
        $mar = "https://www.ufs-online.ru/raspisanie-poezdov/year-time-".$trains;
    }

    if ($mar != null) {
        $url = $mar;   // Парсим годовой график
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

        
        $years = $html->find('div[class="rys-yearly-calendar__item"]');
        foreach($years as $year){
            $month = $year -> find('span[class="rys-calendar__month"]',0)-> plaintext;
            $year_number =  $year -> find('span[class="rys-calendar__year"]',0)-> plaintext;
            
            $table_day = $year -> find('table[class="rys-calendar__calendar"] td');
        $k = 0;
        	$all = null;
        	$all1 = null;
            foreach($table_day as $all_day){
                 $all= $all_day -> find('span[class="rys-calendar__day"]',0) -> find('div[class="rys-calendar__tip"]');
            		$all1= $all_day -> find('span[class="rys-calendar__day"]',0) -> find('div[class="rys-calendar__price"]');
           		if(isset($all[0])){
                 $all[0]->outertext = '';
                }
            if(isset($all1[0])){
            	$all1[0]->outertext = '';
            }
                 $day =  $all_day -> find('span[class="rys-calendar__day"]',0)->innertext;
                 $class = $all_day -> find('span[class="rys-calendar__day"]',0)->attr['class'];
                  if($class == "rys-calendar__day rys-calendar__day_disabled" || $class == "rys-calendar__day rys-calendar__day_active" || $class == "rys-calendar__day rys-calendar__day_active rys-calendar__day_current" || $class == "rys-calendar__day rys-calendar__day_disabled rys-calendar__day_current"){
                     $go = "false";
                     if($class == "rys-calendar__day rys-calendar__day_active" || $class == "rys-calendar__day rys-calendar__day_active rys-calendar__day_current"){
                         $go = "true";
                     }

                    //$day =str_replace('<\/span>', '', $day);
                     $date = $day.".".GetMonth($string = str_replace(' ', '', $month)). ".".str_replace(' ', '', $year_number);
                     
                      $k++;
                  	 if($k <= 31){	
                      $result_date[] = array(
                            "date" => $date,
                            "value" => $go
                        );
                     }
                 }
                 
                 
            }
            
        }


    }
    return $result_date;

}


function GetMonth($month){
    
    if($month == "Январь"){
        return 1;
    }else if($month == "Февраль"){
        return 2;
    }else if($month == "Март"){
        return 3;
    }else if($month == "Апрель"){
        return 4;
    }else if($month == "Май"){
        return 5;
    }else if($month == "Июнь"){
        return 6;
    }else if($month == "Июль"){
        return 7;
    }else if($month == "Август"){
        return 8;
    }else if($month == "Сентябрь"){
        return 9;
    }else if($month == "Октябрь"){
        return 10;
    }else if($month == "Ноябрь"){
        return 11;
    }else if($month == "Декабрь"){
        return 12;
    }else{
        return 0;
    }
}

// закрываем подключение
mysqli_close($link);
?>