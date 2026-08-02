<?php
header('Content-type: application/json');
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// require_once '../api/connection.php'; 
// $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
// mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$arrayCars = $data['cars'];

if($arrayCars != null){


   $array_carr = parseCarriageInfo($arrayCars);
   if (count($array_carr) > 0) {
          echo json_encode(array("data" => $array_carr));
    } else {
         echo json_encode(array('messege' => 'false'));
    }
	
}else{
 	echo json_encode(array('data' => array()));
}

function parseCarriageInfo($array_cars){
    $result = null;
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


?>