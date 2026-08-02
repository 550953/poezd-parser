<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include '../simple_html_dom.php';
include '../../examples/train_carriages.php';

require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$train = $data['train'];
$date_start = $data['date'];
$vagon = $data['vagon'];
//$train = $_GET['train'];
//$date_start = $_GET['date'];
//$vagon = $_GET['vagon'];


if($train != null && $date_start != null && $vagon != null) {

    $dateNow = date("Y-m-d H:i:s");
    $query = "SELECT * FROM `mn_way_info` WHERE `train_name`='" . $train . "' AND `date_start`='" . $date_start . "' ";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $date = $row['date_create'];
            }
        }
        $result = strtotime($dateNow) - strtotime($date);

        if ($result > 60 * 1) {

            deleteFromBase($train, $date_start, $link);
            $array_carr = ParseTrainPass($train, $date_start, $vagon, $link);

            if (!mysqli_ping($link))
                $link = mysqli_connect($host, $user, $password, $database);
            $array_carr = array();
            if (count($array_carr) > 0) {
                saveFromBase($train, $date_start, $vagon, $array_carr, $link);
                echo json_encode(array("data" => $array_carr));
            } else {

                $array_carr = getFromBaseInfo($train, $date_start, $link);
                if (count($array_carr) > 0) {
                    echo json_encode(array("data" => $array_carr));
                } else {
                    echo json_encode(array('messege' => 'false'));
                }
            }

        } else {
            $array_carr = getFromBaseInfo($train, $date_start, $link);
            if (count($array_carr) > 0) {
                echo json_encode(array("data" => $array_carr));
            } else {
                echo json_encode(array('messege' => 'false'));
            }

        }
    } else {

        $array_carr = ParseTrainPass($train, $date_start, $vagon, $link);

        if (!mysqli_ping($link))
            $link = mysqli_connect($host, $user, $password, $database);

        saveFromBase($train, $date_start, $vagon, $array_carr, $link);
        echo json_encode($array_carr);


    }
}else{
    echo json_encode(array('messege' => 'false'));
}

//if(!mysqli_ping($link) )
//            $link = mysqli_connect($host, $user, $password, $database);
//$array_carr1 = getFromBaseInfo($train, $date_start, $link);
//
//if(!mysqli_ping($link) )
//            $link = mysqli_connect($host, $user, $password, $database);
//$array_carr = ParseTrainPass($train, $date_start, $vagon, $link);



//echo "<pre>";
//print_r($array_carr);
//echo "<pre>";
//
//echo "----------------------------------------------------------------------<br>";
//echo "<pre>";
//print_r($array_carr1);
//echo "<pre>";

function getFromBaseInfo($train, $date_start, $link){
    $result_pass = array();

    $query = "SELECT * FROM `mn_way_info` WHERE `train_name`='".$train."' AND `date_start`='".$date_start."' ";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        }
    }

    $result_station = getStationTrain($train, $link);
    for ($i = 0; $i < count($result_station); $i++){
        $carriages = array();
        $query = "SELECT * FROM `mn_pass_info` WHERE `id_train_info`='".$id."' AND `index_st`= ".$i."   ORDER BY `index_st` ASC, `number` ASC";
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
        $result_pass[] = $carriages;
    }

    return $result_pass;
}



function deleteFromBase($train, $date_start, $link){


    $query = "SELECT * FROM `mn_way_info` WHERE `train_name`='".$train."' AND `date_start`='".$date_start."' ";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        }
    }

    $query = "DELETE FROM `mn_pass_info` WHERE `id_train_info` = ".$id;
    mysqli_query($link, $query);

    $query = "DELETE FROM `mn_way_info` WHERE `id` = ".$id;
    mysqli_query($link, $query);


}

function ParseTrainPass($train, $date_start, $vagon, $link){

    $result_station = getStationTrain($train, $link);

    $result_arr = getPassagerInfo($train, $date_start, $result_station);
    $arr = getPassagerEnd($train, $date_start, $result_station, getCarriagesShow($vagon, $result_arr, $result_station));
    $result_arr[] = $arr;
    return $result_arr;
}


function saveFromBase($train, $date_start, $vagon, $result_arr, $link){
    if(count($result_arr)!= 0){

        $query = "INSERT INTO `mn_way_info`(`id`, `train_name`, `vagon`, `date_start`, `date_create`) VALUES (null,'".$train."','".$vagon."', '".$date_start."',NOW())";
        mysqli_query($link, $query);
        $id_root = mysqli_insert_id($link);

        for($i = 0; $i < count($result_arr); $i++){
            $station = $i;
            $array_carr = $result_arr[$i];
            for($j = 0; $j < count($array_carr); $j++) {

                $number = $array_carr[$j]['number'];
                $free = $array_carr[$j]['free'];
                $seats = implode(",", $array_carr[$j]['seats']);

                $query = "INSERT INTO `mn_pass_info`(`id`, `id_train_info`, `index_st`, `number`, `free`, `seats`) VALUES (null,'".$id_root."','".$station."','".$number."','".$free."','".$seats."')";
                mysqli_query($link, $query);

            }


        }


    }
}








function getCarriagesShow($number, $carriages, $result_station){
    $num = 1;
    for ($i = 0; $i < count($result_station)-1; $i++){

        for ($j = 0; $j < count($carriages); $j++){
            if($carriages[$i][$j]['number'] == $number){
                $num = $i +1;
                break;
            }else if($j == count($carriages) - 1){
                $num = count($result_station) - 1;
            }

        }
    }

    return $num;
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

function getPassagerInfo($train, $date_start, $result_station){
    $error = 0;
    $countError = 3;
    for($j = 0; $j < count($result_station) - 1; $j++){
        $from = $result_station[$j]['code'];
        $to = $result_station[$j+1]['code'];
        $date = getDateTime($date_start, $result_station[$j]['day']);
        $time = $result_station[$j]['otpr'];

        for($i=0;  $i < $countError; $i++){

            $array = getTrainCars($from,$to,$date,$time,$train);
            if($array['cars'] == null){
                $error++;
                if($error == $countError){
                    $result_arr[] = null;
                    $error = 0;
                }
            }else{
                $error = 0;
                $result_arr[] = parseCarriageInfo($array['cars']);
                break;
            }
        }
    }
    return $result_arr;
}


function getPassagerEnd($train, $date_start, $result_station, $max){
    $error = 0;
    $countError = 3;
    $from = $result_station[0]['code'];
    $to = $result_station[$max]['code'];
    $date = getDateTime($date_start, $result_station[0]['day']);
    $time = $result_station[0]['otpr'];


        for($i=0;  $i < $countError; $i++){

            $array = getTrainCars($from,$to,$date,$time,$train);
            if($array['cars'] == null){
                $error++;
                echo "error = ".$error;
                if($error == $countError){
                    $result_arr = null;
                    $error = 0;
                    echo "error";
                }
            }else{
                $error = 0;
                $result_arr = parseCarriageInfo($array['cars']);
                break;
            }
        }

    return $result_arr;
}

function getDateTime($dateStart, $day){
    $day = (int)$day;
    $date = strtotime($dateStart);
    $date = strtotime("+".$day." day", $date);
    return date('j.n.Y',$date);

}

function getStationTrain($train, $link){

    $id_train = getIdTrain($train, $link);

    $query = "SELECT * FROM `rx_train_station` WHERE `id_train`=".$id_train." ORDER BY `id` ASC";
    $result = mysqli_query($link,$query);
    if($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {


            $result_station[] = array(
                "code" => $row['code'],
                "otpr" => $row['otpr'],
                "day" => $row['day']
            );
        }
    }
    return $result_station;
}




function getIdTrain($train, $link){
    $id_train = 0;
    $query = "SELECT * FROM `rx_train_info` WHERE `number`='".$train."'";
    $result = mysqli_query($link,$query);
    if($result) {

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }
    }
    return $id_train;
}





// закрываем подключение
mysqli_close($link);
?>