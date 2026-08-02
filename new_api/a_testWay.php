<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$trains = $data['trains'];
$full_name= $data['full_name'];
$dateStart = $data['dates'];




// test("name", "20393", $link);


// function test($name, $code, $link){

// }

// $trains = $_GET['trains'];
// $dateStart = $_GET['dates'];
// $full_name = $_GET['full_name'];

// if($_GET['trains']!= null && $_GET['date'] != null){
//     $result_arr = ParseTrainInfo($_GET['trains'], $_GET['date']);
//     echo "<pre>";
//     print_r($result_arr);
//      echo "<pre>";
//   // echo json_encode($result_arr);
//  }
//  
//  //SELECT rx_train_route.id, rx_train_route.id_station  FROM `rx_train_route`  LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id  WHERE rx_train_route.id_train = 21 AND rx_station_info.name = 'РОСТОВ'
if($trains != null && $full_name != null && $dateStart != null) {

    $dateNow = date("Y-m-d H:i:s");

    $query = "SELECT `id` FROM `rx_train_info` WHERE `number`='".$trains."' AND `date_start` = '".$dateStart."'";

    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);


    if($count > 0){
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $date = $row['recording_date'];
            }
        }
        $result = strtotime($dateNow) - strtotime($date);


        if($result > 86400){
            $result_arr = ParseTrainInfo($trains, $dateStart);
           
            if (!mysqli_ping($link))
                $link = mysqli_connect($host, $user, $password, $database);
            if($result_arr['stations'] != null)
                SaveDateTrainInfo($result_arr, $trains, $full_name, $dateStart, $link );
        }else{
            echo json_encode(getFromBase($trains, $dateStart, $link));
        }
    }else{

        $result_arr = ParseTrainInfo($trains, $dateStart);
        if (!mysqli_ping($link))
            $link = mysqli_connect($host, $user, $password, $database);
        if($result_arr['stations'] != null)
            SaveDateTrainInfo($result_arr, $trains, $full_name, $dateStart, $link );
    }


}


function getFromBase($trains, $date, $link){

    $query = "SELECT `id`, `number` FROM `rx_train_info` WHERE `number`='".$trains."' AND `date_start` = '".$date."'";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        	$number = $row['number'];
        }
    }
	$query = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id LEFT JOIN `rx_train_side` ON rx_train_route.id_station = rx_train_side.id_station WHERE `id_train`= ".$id_train."
    AND `vagon` = 0 AND rx_train_side.train_name = '".$number."' ORDER BY `rx_train_route`.`position` ASC";

	//$query = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id WHERE `id_train`= ".$id_train." AND `vagon` = 0 ORDER BY `rx_train_route`.`position` ASC";
    //$query = "SELECT * FROM `rx_train_station` WHERE `id_train`=".$id_train." AND `vagon` = 0"; 
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        
            hasSoundCode($row['code'], $row['name'],$link);
            $result_station[] = array(
                "name" => $row['name'],
                "code" => $row['code'],
                "prib" => $row['prib'],
                "wait" => $row['wait'],
                "otpr" => $row['otpr'],
                "dist" => $row['dist'],
                "day" => $row['day'],
                "diffTime" => $row['diffTime'],
                "discription" => $row['discription'],
            	"type" => $row['type'],
            	"add_user" => $row['add_user'],
            	"side_none" => $row['none_side'],
            	"side_left" => $row['left_side'],
            	"side_right" => $row['right_side'],
            	"side_over" => $row['over_side'],
            	"trush" => $row['trush'],
            	"water" => $row['water'],
            	"market" => $row['market'],
                "width" => $row['width'],
            	"coal" => $row['coal']
            );
        }
    }
	$query_vagon = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id LEFT JOIN `rx_train_side` ON rx_train_route.id_station = rx_train_side.id_station WHERE `id_train`= ".$id_train."
    AND `vagon` = 1 AND rx_train_side.train_name = '".$number."' ORDER BY `rx_train_route`.`position` ASC";

   // $query_vagon = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id WHERE `id_train`= ".$id_train." AND `vagon` = 1 ORDER BY `rx_train_route`.`position` ASC";
    //$query_vagon = "SELECT * FROM `rx_train_station` WHERE `id_train`=".$id_train." AND `vagon` = 1"; 
    $result_vagon = mysqli_query($link,$query_vagon);
	$result_station_vagon = array();
    if($result_vagon){
        while ($row_vagon = mysqli_fetch_array($result_vagon, MYSQLI_ASSOC)) {
            $result_station_vagon[] = array(
                "name" => $row_vagon['name'],
                "code" => $row_vagon['code'],
                "prib" => $row_vagon['prib'],
                "wait" => $row_vagon['wait'],
                "otpr" => $row_vagon['otpr'],
                "dist" => $row_vagon['dist'],
                "day" => $row_vagon['day'],
            	"diffTime" => $row_vagon['diffTime'],
                "discription" => $row_vagon['discription'],
            	"type" => $row_vagon['type'],
            	"add_user" => $row_vagon['add_user'],
            	"side_none" => $row_vagon['none_side'],
            	"side_left" => $row_vagon['left_side'],
            	"side_right" => $row_vagon['right_side'],
            	"side_over" => $row_vagon['over_side'],
            	"trush" => $row_vagon['trush'],
            	"water" => $row_vagon['water'],
            	"market" => $row_vagon['market'],
                "width" => $row['width'],
            	"coal" => $row_vagon['coal']
            );
        }
    }

    if (count($result_station) > 0) {
        $all_mass = array(  // Формируем массив
            "stations" => $result_station,
            "vagon" => $result_station_vagon,
            "id_train" => $id_train
        );

    }else{
        $all_mass = array(  // Формируем массив
            'messege' => 'false'
        );

    }

    return $all_mass;
}


function ParseTrainInfo($trains, $dateStart)
{

    include '../../examples/train_station_list.php';

    for ($i = 0; $i < 10; $i++) {    // Парсим расписание по первому актуальному дню из списка дат
        $arr_station = get_station($dateStart, $trains);
        //echo $arr_station;
        if (json_decode($arr_station, true)['train'] == null) {
            $all_mass = array('messege' => 'false');
        } else {
            $array = json_decode($arr_station, true);
            break;
        }
    }


    $res = $array;
    $vagon = 0; 
    $all_station_arr = $array['routes']['Stop'];

	$startStOsn =  $array['routes']['Route']['Station'][0];
    $endStOsn =  $array['routes']['Route']['Station'][0];

    if ($all_station_arr == null) {
        $vagon = 1;
    	$startStOsn =  $array['routes'][0]['Route']['Station'][0];
    	$endStOsn =  $array['routes'][0]['Route']['Station'][1];
        $all_station_arr = $array['routes'][0]['Stop'];
    }
    $dist_old = 2;
	$arrayAllStations = array();
	 foreach ($all_station_arr as $station) { //foreach
     		$dist = $station['Distance'];
     		if ($station['Sign'] == null){ //if1
            	$dist_old = $dist;
        		$name = $station['@attributes']['Station'];
            	$new_name = str_replace("'", "", $name);
            	$code = $station['@attributes']['Code'];
            	$prib = $station['ArvTime'];
            	$wait = $station['WaitingTime'];
            	$otpr = $station['DepTime'];
            	$dist = $station['Distance'];
            	$day = $station['Days'];
            	if($station['DiffTime'] != null)
            		$diffTime = str_replace("0","",$station['DiffTime']);
       			else
            		$diffTime = 0;
       			$arrayOsnStations[] = array(
                	"name" => $new_name,
                	"code" => $code,
                	"prib" => $prib,
                	"wait" => $wait,
                	"otpr" => $otpr,
                    "dist" => $dist,
                	"day" => $day, 
                	"diffTime" => $diffTime,
            		"discription" => "",
            		"type" => 0,
            		"add_user" => 0  
            	);
        	} //if1
     }//foreach
  //   $arrayAllStations[0] = $arrayOsnStations;
 	 
     if($vagon == 1 && count($array['routes']) > 1){  //if1
     	$splitVagon = false;
     	$dist_old = 2;
     	 for ($i=1; $i < count($array['routes']); $i++) { ///for1
         	$startStVagon =  $array['routes'][$i]['Route']['Station'][0];
         	if($startStVagon == null){
            	$splitVagon = true;
            	$startStVagon =  $array['routes'][$i]['Route'][0]['Station'][0];
            }
    		$endStVagon =  $array['routes'][$i]['Route']['Station'][1];
            $all_station_arr = $array['routes'][$i]['Stop'];
         	
            $arrayVagonStations = array();
         	$endStVagon = str_replace("'", "", $all_station_arr[0]['@attributes']['Station']);
         	//echo $startStVagon. " - ". $endStVagon."<br>";
         	if($i == 1)
         		$arrayVagonStations = getPartMarshrut($startStVagon, $endStVagon, $arrayOsnStations);
         	if($all_station_arr[0] != null){ //if2
         		foreach ($all_station_arr as $station) { //foreach
            	
            		$dist = $station['Distance'];
                   // echo $dist_old." - ".$dist."<br>";
     				if ($station['Sign'] == null){ //if1
                		$dist_old = $dist;
            			$new_name = str_replace("'", "", $station['@attributes']['Station']);
            			$code = $station['@attributes']['Code'];
            			$prib = $station['ArvTime'];
            			$wait = $station['WaitingTime'];
            			$otpr = $station['DepTime'];
            			$dist = $station['Distance'];
            			$day = $station['Days'];
            				if($station['DiffTime'] != null)
            					$diffTime = str_replace("0","",$station['DiffTime']);
       						else
            					$diffTime = 0;
       					$arrayVagonStations[] = array(
                			"name" => $new_name,
                			"code" => $code,
                			"prib" => $prib,
                			"wait" => $wait,
                			"otpr" => $otpr,
                			"dist" => $dist,
                			"day" => $day, 
                			"diffTime" => $diffTime,
            				"discription" => "",
            				"type" => 0,
            				"add_user" => 0
            			);
        			} //if1
     			}//foreach
            }else{
            	$station = $all_station_arr;
            	$dist = $station['Distance'];
     				if ($station['Sign'] == null){ //if1
                		$dist_old = $dist;
        				$name = $station['@attributes']['Station'];
            			$new_name = str_replace("'", "", $name);
            			$code = $station['@attributes']['Code'];
            			$prib = $station['ArvTime'];
            			$wait = $station['WaitingTime'];
            			$otpr = $station['DepTime'];
            			$dist = $station['Distance'];
            			$day = $station['Days'];
            				if($station['DiffTime'] != null)
            					$diffTime = str_replace("0","",$station['DiffTime']);
       						else
            					$diffTime = 0;
       					$arrayVagonStations[] = array(
                			"name" => $new_name,
                			"code" => $code,
                			"prib" => $prib,
                			"wait" => $wait,
                			"otpr" => $otpr,
                			"dist" => $dist,
                			"day" => $day, 
                			"diffTime" => $diffTime,
            				"discription" => "",
            				"type" => 0,
            				"add_user" => 0
            			);
                    }
            }//if2
         	$arrayAllStations[] = getNotCloneArray($arrayVagonStations);
         }  //for1
     }//if1


     if($splitVagon && count($arrayAllStations) > 1){
     	$newArray = array();
     	foreach($arrayAllStations[0] as $st){
        	$newArray[] = $st;
        }
     foreach($arrayAllStations[1] as $st){
        	$newArray[] = $st;
        }
        $arrayAllStations[0] = $newArray;
     }





    if (count($arrayOsnStations) > 0) {
        $all_mass = array(  // Формируем массив
            "stations" => getNotCloneArray($arrayOsnStations),
            "vagon" => $arrayAllStations[0],
           // "rer" => $res,
        	 "id_train" => 0
        
            
        );

    }else{
        $all_mass = array(  // Формируем массив
            'messege' => 'false'
        );

    }

    return $all_mass;
}
function getNotCloneArray($arrStation){
	$dist_old = 2;
	$new_array = array();
	foreach($arrStation as $station){
    	$dist = $station['dist'];
    	if($dist_old != $dist){
        	$dist_old = $dist;
        	$new_array[] = $station;
        }
    }
return $new_array;
}

function getPartMarshrut($startSt, $endst, $arrStation){
	$result_arr = array();
    $record = false;
	foreach($arrStation as $station){
    	if($station['name'] == $startSt){
        	$record = true;
        }
    	if($station['name'] == $endst){
        	$record = false;
        }
    	if($record){
        	$result_arr[] = $station;
        }
    }
return $result_arr;
}

function hasSound($stationArray,$link){

foreach ($stationArray['stations'] as $st){
    $filename = '../sounds/otpr5min_mp3/'.$st['code'].'.mp3';
	if(!file_exists($filename)){
    	$query = "SELECT * FROM `not_sound` WHERE `code` = '".$st['code']."' ";
    	$result = mysqli_query($link,$query);
		$count = mysqli_num_rows($result);
        if($count == 0){
        	$query_sound = "INSERT INTO `not_sound` (`id`, `code`, `name`) VALUES (NULL,'".$st['code']."','".$st['name']."')";
        	mysqli_query($link, $query_sound);
        }
    }
}

}

function hasSoundCode($code, $name,$link){
	
    $filename = '../sounds/otpr5min_mp3/'.$code.'.mp3';
	if(!file_exists($filename)){
    	$query = "SELECT * FROM `not_sound` WHERE `code` = '".$code."' ";
    	$result = mysqli_query($link,$query);
		$count = mysqli_num_rows($result);
        if($count == 0){
        	$query_sound = "INSERT INTO `not_sound` (`id`, `code`, `name`) VALUES (NULL,'".$code."','".$name."')";
        	mysqli_query($link, $query_sound);
        }
    }
    
}

function SaveDateTrainInfo($stationArray, $trains, $fullTrain, $date, $link){


    $query = "SELECT `id` FROM `rx_train_info` WHERE `number`='".$trains."' AND `date_start` = '".$date."'";
    $result = mysqli_query($link,$query);
    if($result){
        $id_train = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        }

        $query = "DELETE FROM `rx_train_route` WHERE `id_train` = ".$id_train;
        mysqli_query($link, $query);

        $query = "DELETE FROM `rx_train_info` WHERE `id` = ".$id_train;
        mysqli_query($link, $query);
    }


    $query = "INSERT INTO `rx_train_info`(`number`, `full_name`, `date_start`, `recording_date`) VALUES ('".$trains."','".$fullTrain."','".$date."',NOW())";
    mysqli_query($link, $query);
    $id_train = mysqli_insert_id($link);

    

    
    hasSound($stationArray,$link);
	$i = 0;
    foreach ($stationArray['stations'] as $st){
        $id_station = SaveStation($st['name'], $st['code'], $link);
        saveDiffTime($st['code'], $st['diffTime'], $link);
    	$id_side = SaveTrainSide($trains, $id_station['id'], $link);
    if(isset($id_side)){
    		$side_none = $id_side['none_side'];
            $side_left = $id_side['left_side'];
            $side_right = $id_side['right_side'];
           	$side_over = $id_side['over_side'];
    }else{
    	$side_none = 1;
    	$side_left = 0;
    	$side_right = 0;
    	$side_over = 0;
    }
    
    if(isset($id_station)){
       		$trush = $id_station['trush'];
            $water = $id_station['water'];
            $market = $id_station['market'];
           	$coal = $id_station['coal'];
            $width = $id_station['width'];
        	$new_arr_station[] = array(
                        "name" => $st['name'],
                        "code" => $st['code'],
                        "prib" => $st['prib'],
                        "wait" => $st['wait'],
                        "otpr" => $st['otpr'],
                        "dist" => $st['dist'],
                        "day" => $st['day'],
                    	"diffTime" => $st['diffTime'],
                     	"discription" => $st['discription'],
            			"type" => $st['type'],
            			"add_user" => $st['add_user'],
            			"side_none" => $side_none,
            			"side_left" => $side_left,
            			"side_right" => $side_right,
            			"side_over" => $side_over,
            			"trush" => $trush,
            			"water" => $water,
            			"market" => $market,
                        "width" => $width,
            			"coal" => $coal
            );
        }

    
    	$query = "INSERT INTO `rx_train_route` (`id`, `id_train`, `id_station`, `prib`, `wait`, `otpr`, `dist`, `day`, `vagon`, `diffTime`, `position`) VALUES (NULL, '".$id_train."', '".$id_station['id']."', '".$st['prib']."','".$st['wait']."','".$st['otpr']."','".$st['dist']."','".$st['day']."','0','".$st['diffTime']."', '".$i."')";
        mysqli_query($link, $query);
   		$i++;
    }
    $j = 0;
	//foreach ($stationArray['vagon'] as $vag){
    foreach ($stationArray['vagon'] as $st){
    	$id_station = SaveStation($st['name'], $st['code'], $link);
   		$id_side = SaveTrainSide($trains, $id_station['id'], $link);
    if(isset($id_side)){
    		$side_none = $id_side['none_side'];
            $side_left = $id_side['left_side'];
            $side_right = $id_side['right_side'];
           	$side_over = $id_side['over_side'];
    }else{
    	$side_none = 1;
    	$side_left = 0;
    	$side_right = 0;
    	$side_over = 0;
    }

    
    	if(isset($id_station)){
       		$trush = $id_station['trush'];
            $water = $id_station['water'];
            $market = $id_station['market'];
           	$coal = $id_station['coal'];
        	$new_arr_vagon[] = array(
                        "name" => $st['name'],
                        "code" => $st['code'],
                        "prib" => $st['prib'],
                        "wait" => $st['wait'],
                        "otpr" => $st['otpr'],
                        "dist" => $st['dist'],
                        "day" => $st['day'],
                    	"diffTime" => $st['diffTime'],
                     	"discription" => $st['discription'],
            			"type" => $st['type'],
            			"add_user" => $st['add_user'],
            			"side_none" => $side_none,
            			"side_left" => $side_left,
            			"side_right" => $side_right,
            			"side_over" => $side_over,
            			"trush" => $trush,
            			"water" => $water,
            			"market" => $market,
            			"width" => $width,
            			"coal" => $coal
            );
        }

    	$query = "INSERT INTO `rx_train_route` (`id`, `id_train`, `id_station`, `prib`, `wait`, `otpr`, `dist`, `day`, `vagon`, `diffTime`, `position`) VALUES (NULL, '".$id_train."', '".$id_station['id']."', '".$st['prib']."','".$st['wait']."','".$st['otpr']."','".$st['dist']."','".$st['day']."','1','".$st['diffTime']."', '".$j."')";
        mysqli_query($link, $query);
    $j++;
    }
    //$result_arr[] = $new_arr_vagon;
   // }
    $stationArray['stations'] = $new_arr_station;
    $stationArray['vagon'] = $new_arr_vagon;
	$stationArray["id_train"] = $id_train;
 	echo json_encode($stationArray);

}

function SaveTrainSide($trains, $id_station, $link){
	$query = "SELECT * FROM `rx_train_side` WHERE `train_name` = '".$trains."' AND `id_station` = '".$id_station."'";
    $result = mysqli_query($link,$query);
	$count = mysqli_num_rows($result);
if($result){
    if($count > 0){
    $id = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        }
    	return $id;
    }else{
    	$query = "INSERT INTO `rx_train_side`(`id`, `id_station`, `train_name`) VALUES (NULL,'".$id_station."','".$trains."')";
        $result = mysqli_query($link,$query);
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        }
        //$id = mysqli_insert_id($link);
    	
        return $id;
    }
}

}

function SaveStation($name, $code, $link){
	$query = "SELECT * FROM `rx_station_info` WHERE `code` = '".$code."'";
    $result = mysqli_query($link,$query);
	$count = mysqli_num_rows($result);

if($result){
    if($count > 0){
    $id = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        }
    	return $id;
    }else{
    	$query = "INSERT INTO `rx_station_info` (`id`, `name`, `code`, `type`, `add_user`) VALUES (NULL, '".$name."', '".$code."', 0, 0)";
        $result = mysqli_query($link,$query);
     	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        }
        //$id = mysqli_insert_id($link);
    	
        return $id;
    }
}

}

function saveDiffTime($code, $diff, $link){
	$query = "UPDATE `express_stations` SET `gmt` = '".$diff."' WHERE `express_stations`.`code` = ".$code.""; 
	$result = mysqli_query($link, $query);
}


// закрываем подключение
mysqli_close($link);
?>
