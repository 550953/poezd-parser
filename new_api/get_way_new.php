<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//include '../simple_html_dom.php';

include '../get_way_and_day.php';
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

//function get_web_page( $url )
//{
//    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
//
//    $ch = curl_init( $url );
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//    curl_setopt($ch, CURLOPT_ENCODING, "");
//    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
//    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//
//    $content = curl_exec( $ch );
//    $err     = curl_errno( $ch );
//    $errmsg  = curl_error( $ch );
//    $header  = curl_getinfo( $ch );
//    curl_close( $ch );
//
//    $header['errno']   = $err;
//    $header['errmsg']  = $errmsg;
//    $header['content'] = $content;
//    return $header;
//}
//


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
//
//$trains = $_GET['trains'];
//$full_name = $_GET['full_name'];
$trains = $data['trains'];
$full_name= $data['full_name'];

if($trains != null && $full_name != null) {

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


        if ($result > 86400 * 15) {
            $result_arr = getTrain($trains);
            if(count($result_arr) > 0) {
                echo json_encode($result_arr);

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
        $result_arr = getTrain($trains);
        if(count($result_arr) > 0) {
            echo json_encode($result_arr);
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

        }

    }
    return $result_date;

}

// закрываем подключение
mysqli_close($link);
?>