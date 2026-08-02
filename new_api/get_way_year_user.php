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
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
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



$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
//
// $trains = $_GET['trains'];
// $full_name = $_GET['full_name'];
$trains = $data['trains'];
$full_name= $data['full_name'];

if($trains != null && $full_name != null) {

    $result_arr = ParseTrainInfo($trains);
    if(count($result_arr) > 0) {
                
    	$all_mass = array(  // Формируем массив
              "dates" => $result_arr
         );
         echo json_encode($all_mass);

               
    }else{
    	$all_mass = array(  // Формируем массив
                    'messege' => 'false'
        );
    echo json_encode($all_mass, JSON_FORCE_OBJECT);
    }

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

        
        $years = $html->find('div[class="rys-yearly-calendar"]');
        foreach($years as $year){
            $month = $year -> find('span[class="rys-calendar__month"]',0)-> plaintext;
            $year_number =  $year -> find('span[class="rys-calendar__year"]',0)-> plaintext;
            
            $table_day = $year -> find('table[class="rys-calendar__calendar"] td');
        	$all = null;
        	
            foreach($table_day as $all_day){
                 $all= $all_day -> find('span[class="rys-calendar__day"]',0) -> find('div[class="rys-calendar__tip"]');
                 $all[0]->outertext = '';
                 $day =  $all_day -> find('span[class="rys-calendar__day"]',0)->innertext;
                 $class = $all_day -> find('span[class="rys-calendar__day"]',0)->attr['class'];
                  if($class == "rys-calendar__day rys-calendar__day_disabled" || $class == "rys-calendar__day rys-calendar__day_active" || $class == "rys-calendar__day rys-calendar__day_active rys-calendar__day_current" || $class == "wg-calendar__day wg-calendar__day_disabled wg-calendar__day_current"){
                     $go = "false";
                     if($class == "rys-calendar__day rys-calendar__day_active" || $class == "rys-calendar__day rys-calendar__day_active rys-calendar__day_current"){
                         $go = "true";
                     }

                    //$day =str_replace('<\/span>', '', $day);
                     $date = $day.".".GetMonth($string = str_replace(' ', '', $month)). ".".str_replace(' ', '', $year_number);
                     
                      $result_date[] = array(
                            "date" => $date,
                            "value" => $go
                        );
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