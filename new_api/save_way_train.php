<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include '../simple_html_dom.php';

require_once '../api/connection.php'; // подключаем скрипт
header('Content-type: application/json');
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
//$train = $_GET['train'];
//$full_name = $_GET['full_name'];
$trains = $data['trains'];
$full_name= $data['full_name'];


isHaveTrain($trains, $full_name, $link);


function isHaveTrain($trains, $full_name, $link){

    $dateNow = date("Y-m-d H:i:s");

    $query = "SELECT * FROM `rx_train_info` WHERE `number`='".$trains."'";

    $result = mysqli_query($link,$query);
    $count = mysqli_num_rows($result);


    if($count > 0){
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $date = $row['recording_date'];
            }
        }
        $result = strtotime($dateNow) - strtotime($date);


        if($result > 86400*5){
            echo json_encode(ParseTrainInfo($trains, $full_name, $link ));
        }else{
            echo json_encode(getFromBase($trains, $full_name, $link));
        }
    }else{

        echo json_encode(ParseTrainInfo($trains, $full_name, $link));
    }


}
function getFromBase($trains, $full_name, $link){

    $query = "SELECT * FROM `rx_train_info` WHERE `number`='".$trains."'";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }
    }

    $query = "SELECT * FROM `rx_train_station` WHERE `id_train`=".$id_train;
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_station[] = array(
                "name" => $row['name'],
                "code" => $row['code'],
                "prib" => $row['prib'],
                "wait" => $row['wait'],
                "otpr" => $row['otpr'],
                "dist" => $row['dist'],
                "day" => $row['day']
            );
        }
    }

    $query = "SELECT * FROM `rx_train_date` WHERE `id_train`=".$id_train;
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_date[] = array(
                "date" => $row['date'],
                "value" => $row['value']
            );
        }
    }

    $all_mass = array(  // Формируем массив
        "dates" => $result_date,
        "stations" => $result_station
    );

    return $all_mass;
}


function ParseTrainInfo($trains, $full_name, $link)
{

    if ($trains != null) {       //Парсим туту на ссылку годового графика

        $url = "https://www.tutu.ru/poezda/search_train.php?train=" . $trains;
        $result = get_web_page($url);
        $err = 0;
        $i = 0;
        if ($result['errno'] != 0) {
            $result = get_web_page($url);
            $err = 1;
        }

        if ($result['http_code'] != 200) {
            $err = 1;
        }

        $page = $result['content'];

        $html = str_get_html($page);

        if ($html->find('table[id=train_list_table] tr')) {
            foreach ($html->find('table[id=train_list_table] tr') as $divs) {
                if (!$divs->find('p[class="trailer"]')) {
                    if ($divs->find('div[class="reviews"]')) {
                        foreach ($divs->find('div[class="reviews"]', 0)->find('ul li a') as $divss) {
                            $del = array("/poezda/");
                            if ($i == 0 || $i == count($divs->find('div[class="reviews"]', 0)->find('ul li a')) - 1) {
                                $arr[] = str_replace($del, "", $divss->href);
                            }
                            $i++;
                        }
                        $links['link'] = $arr;
                    }
                }
            }
        }

        if ($err == 0) {
            $mar = $links['link'][1];   // Вот ссылка на годовой график поезда
        } else {
            $mar = null;
        }

    } else {
        $all_mass = array('messege' => 'false');
    }

    if ($mar != null) {
        $url = "https://www.tutu.ru/poezda/" . $mar;   // Парсим годовой график
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


        foreach ($html->find('table[class="graph_table"] td[data-date]') as $tmp) {  // Парсим даты поезда

            $findme = 'never_date';
            $mystring = $tmp->attr['class'];
            $pos = stripos($mystring, $findme);
            if ($pos !== false) {
                $day = "false";
            } else {
                $day = "true";
            }
            $date = $tmp->attr['data-date'];
            $result_date[] = array(
                "date" => $date,
                "value" => $day
            );

            $find = 'last_date';
            $string = $tmp->attr['class'];
            $poster = stripos($string, $find);
            if ($poster === false) {
                $find = 'never_date';
                $string = $tmp->attr['class'];
                $poster = stripos($string, $find);
                if ($poster === false) {
                    $days[] = $tmp->attr['data-date'];
                }
            }


        }
        $actual_day = $days[0];  //Актуальный день в котрый ходит поезд


        include '../../examples/train_station_list.php';

        for ($i = 0; $i < 10; $i++) {    // Парсим расписание по первому актуальному дню из списка дат
            $arr_station = get_station($actual_day, $trains);
            //echo $arr_station;
            if (json_decode($arr_station, true)[train] == null) {
                $all_mass = array('messege' => 'false');
            } else {
                $array = json_decode($arr_station, true);
                break;
            }
        }

        $all_station_arr = $array['routes']['Stop'];
        if ($all_station_arr == null) {
            $all_station_arr = $array['routes'][0]['Stop'];
        }

        $dist_old = 2;
        foreach ($all_station_arr as $station) {
            $dist = $station['Distance'];
            if ($station['Sign'] == null && $dist_old != $dist) {
                $dist_old = $dist;

                $name = $station['@attributes']['Station'];
                $code = $station['@attributes']['Code'];
                $prib = $station['ArvTime'];
                $wait = $station['WaitingTime'];
                $otpr = $station['DepTime'];
                $dist = $station['Distance'];
                $day = $station['Days'];

                $result_arr[] = array(
                    "name" => $name,
                    "code" => $code,
                    "prib" => $prib,
                    "wait" => $wait,
                    "otpr" => $otpr,
                    "dist" => $dist,
                    "day" => $day
                );

            }
        }



        $all_mass = array(  // Формируем массив
            "dates" => $result_date,
            "stations" => $result_arr
        );

        SaveDateTrainInfo($result_date, $result_arr, $trains, $full_name, $link );

    } else {
        $all_mass = array('messege' => 'false');
    }
    return $all_mass;
}

function SaveDateTrainInfo($dateArray, $stationArray, $trains, $fullTrain, $link){


    $query = "SELECT * FROM `rx_train_info` WHERE `number`='".$trains."'";
    $result = mysqli_query($link,$query);
    if($result){
        $id_train = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }
        $query = "DELETE FROM `rx_train_date` WHERE `id_train` = ".$id_train;
        mysqli_query($link, $query);

        $query = "DELETE FROM `rx_train_station` WHERE `id_train` = ".$id_train;
        mysqli_query($link, $query);

        $query = "DELETE FROM `rx_train_info` WHERE `number` = '".$trains."'";
        mysqli_query($link, $query);
    }



    $query = "INSERT INTO `rx_train_info`(`number`, `full_name`, `recording_date`) VALUES ('".$trains."','".$fullTrain."',NOW())";
    mysqli_query($link, $query);
    $id_train = mysqli_insert_id($link);

    foreach ($dateArray as $d){
        $query = "INSERT INTO `rx_train_date`(`id`, `id_train`, `date`, `value`) VALUES (null, '".$id_train."', '".$d['date']."', '".$d['value']."')";
        mysqli_query($link, $query);
    }

    foreach ($stationArray as $st){
        $query = "INSERT INTO `rx_train_station`(`id`, `id_train`, `name`, `code`, `prib`, `wait`, `otpr`, `dist`, `day`) VALUES 
        (null,'".$id_train."','".$st['name']."','".$st['code']."','".$st['prib']."','".$st['wait']."','".$st['otpr']."','".$st['dist']."','".$st['day']."')";
        mysqli_query($link, $query);
    }



}



// закрываем подключение
mysqli_close($link);
?>