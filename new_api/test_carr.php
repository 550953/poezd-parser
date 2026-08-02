<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include '../simple_html_dom.php';
include '../../examples/train_carriages.php';

require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
header('Content-type: application/json');
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if(isset($_GET['train'])){
	$train = $_GET['train'];
	$codeFrom = $_GET['codeFrom'];
	$codeTo = $_GET['codeTo'];
	$date_start = $_GET['date'];
	$timeOtpr = $_GET['timeOtpr'];
	$array_carr = getTrainCars($train, $date_start, $codeFrom, $codeTo, $timeOtpr);
    //$array_carr = getTrainCars($codeFrom, $codeTo, $date_start, $timeOtpr, $train);
	print_r($array_carr);
}

//

// if($train != null && $codeFrom != null && $codeTo != null && $date_start != null ){

//     $dateNow = date("Y-m-d H:i:s");
//     $query = "SELECT * FROM `mn_way_info` WHERE `train_name`='".$train."' AND `date_start`='".$date_start."'  AND `code_from`='".$codeFrom."'  AND `code_to`='".$codeTo."' ";
//     $result = mysqli_query($link, $query);
//     $count = mysqli_num_rows($result);

//     if ($count > 0) {
//         if ($result) {
//             while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//                 $date = $row['date_create'];
//             }
//         }
//         $result = strtotime($dateNow) - strtotime($date);

//         if ($result > 60 * 15) {


//             $array_carr = ParseTrainPass($train, $date_start, $codeFrom, $codeTo,$timeOtpr);

//             if (!mysqli_ping($link))
//                 $link = mysqli_connect($host, $user, $password, $database);

//             if (count($array_carr) > 0) {
//                 deleteFromBase($train, $date_start, $codeFrom, $codeTo, $link);
//                 saveFromBase($train, $date_start, $codeFrom, $codeTo, $array_carr, $link);
//                 echo json_encode(array("data" => $array_carr));
//             } else {
//                 $array_carr = getFromBaseInfo($train, $date_start, $codeFrom, $codeTo, $link);
//                 if (count($array_carr) > 0) {
//                     echo json_encode(array("data" => $array_carr));
//                 } else {
//                     echo json_encode(array('messege' => 'false'));
//                 }
//             }

//         } else {
//             $array_carr = getFromBaseInfo($train, $date_start, $codeFrom, $codeTo, $link);
//             if (count($array_carr) > 0) {
//                 echo json_encode(array("data" => $array_carr));
//             } else {
//                 echo json_encode(array('messege' => 'false'));
//             }

//         }
//     } else {

//         $array_carr = ParseTrainPass($train, $date_start, $codeFrom, $codeTo, $timeOtpr);
//          if (count($array_carr) > 0) {
//                 echo json_encode(array("data" => $array_carr));
//          		if (!mysqli_ping($link))
//             		$link = mysqli_connect($host, $user, $password, $database);
//         			saveFromBase($train, $date_start, $codeFrom, $codeTo, $array_carr, $link);
//             } else {
//                 echo json_encode(array('messege' => 'false'));
//             }



//     }
// }else{
//     echo json_encode(array('messege' => 'false'));
// }



function getFromBaseInfo($train, $date_start, $codeFrom, $codeTo, $link){
    $query = "SELECT * FROM `mn_way_info` WHERE `train_name`='".$train."' AND `date_start`='".$date_start."'  AND `code_from`='".$codeFrom."'  AND `code_to`='".$codeTo."' ";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        }
    }

    $carriages = array();
    $query = "SELECT * FROM `mn_pass_info` WHERE `id_info`='".$id."' ORDER BY `number` ASC";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $carriage = array(
                "number" => $row['number'],
                "free" => $row['free'],
                "seats" => explode(",", $row['seats'])
            );
            $carriages[] = $carriage;

        }
    }

//    echo "<pre>";
//    print_r($carriages);
//    echo "<pre>";

    return $carriages;
}


function saveFromBase($train, $date_start, $codeFrom, $codeTo, $array_carr, $link){
    if(count($array_carr)!= 0){

        $query = "INSERT INTO `mn_way_info`(`id`, `train_name`, `code_from`, `code_to`, `date_start`, `date_create`) VALUES (null,'".$train."','".$codeFrom."','".$codeTo."','".$date_start."', NOW())";
        mysqli_query($link, $query);
        $id_root = mysqli_insert_id($link);

        for($i = 0; $i < count($array_carr); $i++){
            $number = $array_carr[$i]['number'];
            $free = $array_carr[$i]['free'];
            $seats = implode(",", $array_carr[$i]['seats']);

            $query = "INSERT INTO `mn_pass_info`(`id`, `id_info`, `number`, `free`, `seats`) VALUES (null,'".$id_root."','".$number."','".$free."','".$seats."')";
            mysqli_query($link, $query);

        }


    }
}


function ParseTrainPass($train, $date_start, $codeFrom, $codeTo,$timeOtpr){

    $error = 0;
    $countError = 3;
    for($i=0;  $i < $countError; $i++){

        $array = getTrainCars($codeFrom, $codeTo, $date_start, $timeOtpr, $train);
        if($array['cars'] == null){
            $error++;
            if($error == $countError){
                $result_arr = null;
                $error = 0;
            }
        }else{
            $error = 0;
            $result_arr = parseCarriageInfo($array['cars']);
            break;
        }
    }



    return $result_arr;

}

function parseCarriageInfo($array_cars){
    foreach ($array_cars as $car){
        $number = $car['cnumber'];
        $free = $car['seats'];
        $seats = $car['places'];
        $final_seat = array();
        $freeseat = 0;
        foreach($free as $seat){
            $freeseat += $seat['free'];
        }

        $seats_arr = explode(",", $seats);
        $findme = "-";
        $seat_2 = array();
        foreach($seats_arr as $seat){
            $search = stripos($seat, $findme);
            if($search === false){
                $seat_2[] = $seat;
            }else{
                $lin_arr = explode($findme, $seat);
                for ($i = $lin_arr[0]; $i <= $lin_arr[1]; $i++){
                    $seat_2[] = $i;
                }
            }

        }

        foreach($seat_2 as $se){
            $se = substr($se, 0, 3);
            $final_seat[] = (int)$se;

        }

        $result[] =  array(
            "number" => $number,
            "free" => $freeseat,
            "seats" => $final_seat
        );
    }
    return $result;
}



function deleteFromBase($train, $date_start, $code_from, $code_to, $link){


$query = "SELECT * FROM `mn_way_info` WHERE `train_name`='".$train."' AND `date_start`='".$date_start."'  AND `code_from`='".$code_from."'  AND `code_to`='".$code_to."' ";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        }
    }

    $query = "DELETE FROM `mn_pass_info` WHERE `id_info` = ".$id;
    mysqli_query($link, $query);

    $query = "DELETE FROM `mn_way_info` WHERE `id` = ".$id;
    mysqli_query($link, $query);


}



// закрываем подключение
mysqli_close($link);
?>