<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');


include '../simple_html_dom.php';

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

function getName($url){

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
    if(!isset($html)){
       return;
    }

    $h1 = $html->find('h1[class="title__mainTitle__11h-C o3310 o3328 o3313"]',0)->innertext;
	
	$pieces = explode(": ", $h1);
        $result = $pieces[count($pieces) - 1];
		return $result;
    
}

require_once '../api/connection.php'; 
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$trains = $data['trains'];
$full_name= $data['full_name'];
$dateStart = $data['dates'];

$arrayRoute = $data['route'];


if($trains != null && $full_name != null && $dateStart != null && $arrayRoute != null) {
 
    $dateNow = date("Y-m-d H:i:s");

    $query = "SELECT `id` FROM `rx_train_info` WHERE `number`='".$trains."' AND `date_start` = '".$dateStart."'";

    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);


    if($count > 0){
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	//if(isset($row['recording_date']))
                $date = $row['recording_date'];
            }
        }
        $result = strtotime($dateNow) - strtotime($date);


        if($result > 86400){
            $result_arr = getArrayStation($arrayRoute);
            if (!mysqli_ping($link))
                $link = mysqli_connect($host, $user, $password, $database);
            if($result_arr['stations'] != null)
                SaveDateTrainInfo($result_arr, $trains, $full_name, $dateStart, $link );
        }else{
            echo json_encode(getFromBase($trains, $dateStart, $link));
        }
    }else{

        $result_arr = getArrayStation($arrayRoute);
   	
        if (!mysqli_ping($link))
            $link = mysqli_connect($host, $user, $password, $database);
        if($result_arr['stations'] != null)
            SaveDateTrainInfo($result_arr, $trains, $full_name, $dateStart, $link );
    }
}






function getArrayStation($array){

if ($array == null) {
	$all_mass = array('messege' => 'false');
	return $all_mass;
} 
//return array('messege' => count($array));

    $res = $array;
    $vagon = 0; 

    $all_station_arr = $array['Routes']['Stop'];

	$startStOsn =  $array['Routes']['Route']['Station'][0];
    $endStOsn =  $array['Routes']['Route']['Station'][0];

    if ($all_station_arr == null) {
        $vagon = 1;
    	$startStOsn =  $array['Routes'][0]['Route']['Station'][0];
    	$endStOsn =  $array['Routes'][0]['Route']['Station'][1];
        $all_station_arr = $array['Routes'][0]['Stop'];
    }

    $dist_old = 2;
	$arrayAllStations = array();
	 foreach ($all_station_arr as $station) { //foreach
     		$dist = $station['Distance'];
     		if ($station['Sign'] == null){ //if1
            	$dist_old = $dist;
                if(isset($station['Station'])){
        			$name = $station['Station'];
            		$new_name = str_replace("'", "", $name);
                }else{
                	$url = "https://www.tutu.ru/poezda/station_d.php?nnst=".$station['Code'];
   					$name = getName($url);
                	mb_internal_encoding('UTF-8');
					setlocale(LC_CTYPE, 'ru_RU');
                	$new_name = mb_strtoupper($name);
                }
            	//if(isset( $station['Code']))
            	$code = $station['Code'];
            //if(isset( $station['ArvTime']))
            	$prib = $station['ArvTime'];
            //if(isset( $station['WaitingTime']))
            	$wait = $station['WaitingTime'];
            //if(isset( $station['DepTime']))
            	$otpr = $station['DepTime'];
           // if(isset( $station['Distance']))
            	$dist = $station['Distance'];
           // if(isset( $station['Days']))
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
 	 
     if( $vagon == 1 && count($array['Routes']) > 1){  //if1
     	$splitVagon = false;
     	$dist_old = 2;
     	 for ($i=1; $i < count($array['Routes']); $i++) { ///for1
         	$startStVagon =  $array['Routes'][$i]['Route']['Station'][0]['content'];
         //return array('messege' => $startStVagon);
         	if($startStVagon == null){
            	$splitVagon = true;
            	$startStVagon =  $array['Routes'][$i]['Route'][0]['Station'][0]['content'];
            }
    		$endStVagon =  $array['Routes'][$i]['Route']['Station'][1];
            $all_station_arr = $array['Routes'][$i]['Stop'];
         	
            $arrayVagonStations = array();
         	$endStVagon = str_replace("'", "", $all_station_arr[0]['Station']);
         
         	//echo $startStVagon. " - ". $endStVagon."<br>";
         	if($i == 1)
         		$arrayVagonStations = getPartMarshrut($startStVagon, $endStVagon, $arrayOsnStations);
           // return array('messege' => $startStVagon);
         	if($all_station_arr[0] != null){ //if2
         		foreach ($all_station_arr as $station) { //foreach
            		
            		$dist = $station['Distance'];
                   // echo $dist_old." - ".$dist."<br>";
     				if ($station['Sign'] == null){ //if1
                		$dist_old = $dist;
            			$new_name = str_replace("'", "", $station['Station']);
                    	
            			$code = $station['Code'];
                    //if(isset($station['ArvTime']))
            			$prib = $station['ArvTime'];
                   // if(isset($station['WaitingTime']))
            			$wait = $station['WaitingTime'];
                   // if(isset($station['DepTime']))
            			$otpr = $station['DepTime'];
                   // if(isset($station['Distance']))
            			$dist = $station['Distance'];
                   // if(isset($station['Days']))
            			$day = $station['Days'];
            				if( $station['DiffTime'] != null)
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
            	//if(isset($station['Distance']))
            		$dist = $station['Distance'];
     				if ($station['Sign'] == null){ //if1
                		$dist_old = $dist;
                    	//if(isset($station['Station']))
        				$name = $station['Station'];
            			$new_name = str_replace("'", "", $name);
                    //if(isset($station['Code']))
            			$code = $station['Code'];
                   // if(isset($station['ArvTime']))
            			$prib = $station['ArvTime'];
                   // if(isset($station['WaitingTime']))
            			$wait = $station['WaitingTime'];
                   // if(isset( $station['DepTime']))
            			$otpr = $station['DepTime'];
                    //if(isset($station['Distance']))
            			$dist = $station['Distance'];
//if(isset($station['Days']))
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


function getFromBase($trains, $date, $link){

    $query = "SELECT `id`, `number` FROM `rx_train_info` WHERE `number`='".$trains."' AND `date_start` = '".$date."'";
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_train = $row['id'];
        	$number = $row['number'];
        }
    }
	$query = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id LEFT JOIN `rx_train_side` ON rx_train_route.id_station = rx_train_side.id_station LEFT JOIN `station_list` ON rx_station_info.code = station_list.code WHERE `id_train`= ".$id_train."
    AND `vagon` = 0 AND rx_train_side.train_name = '".$number."' ORDER BY `rx_train_route`.`position` ASC";

	//$query = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id WHERE `id_train`= ".$id_train." AND `vagon` = 0 ORDER BY `rx_train_route`.`position` ASC";
    //$query = "SELECT * FROM `rx_train_station` WHERE `id_train`=".$id_train." AND `vagon` = 0"; 
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
            	"coal" => $row['coal'],
            	"width" => $row['width'],
            	"sept" => $row['sept'],
            	"slag" => $row['slag'],
            	"country" => $row['country']
            );
        }
    }
	$query_vagon = "SELECT * FROM `rx_train_route` LEFT JOIN `rx_station_info` ON rx_train_route.id_station = rx_station_info.id LEFT JOIN `rx_train_side` ON rx_train_route.id_station = rx_train_side.id_station LEFT JOIN `station_list` ON rx_station_info.code = station_list.code WHERE `id_train`= ".$id_train."
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
            	"coal" => $row_vagon['coal'],
            	"width" => $row_vagon['width'],
            	"sept" => $row_vagon['sept'],
            	"slag" => $row_vagon['slag'],
            	"country" => $row['country']
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


    // $query = "INSERT INTO `rx_train_info`(`number`, `full_name`, `date_start`, `recording_date`) VALUES ('".$trains."','".$fullTrain."','".$date."',NOW())";
    // mysqli_query($link, $query);
    // $id_train = mysqli_insert_id($link);



	$i = 0;
    foreach ($stationArray['stations'] as $st){
        $id_station = SaveStation($st['name'], $st['code'], $link);
    	$id_side = SaveTrainSide($trains, $st['code'], $link);
        
    	//$id_side = SaveTrainSide($trains, $id_station['id'], $link);
    if(isset($id_side)){
    		$side_none = $id_side['side_none'];
            $side_left = $id_side['side_left'];
            $side_right = $id_side['side_right'];
           	$side_over = $id_side['side_over'];
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
    		$sept = $id_station['sept'];
    		$slag = $id_station['slag'];
    		$country = $id_station['country'];
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
            			"coal" => $coal,
            			"width" => $width,
            			"sept" => $sept,
            			"slag" => $slag,
            			"country" => $country
            			
            			
            			
            );
        }

    
    	// $query = "INSERT INTO `rx_train_route` (`id`, `id_train`, `id_station`, `prib`, `wait`, `otpr`, `dist`, `day`, `vagon`, `diffTime`, `position`) VALUES (NULL, '".$id_train."', '".$id_station['id']."', '".$st['prib']."','".$st['wait']."','".$st['otpr']."','".$st['dist']."','".$st['day']."','0','".$st['diffTime']."', '".$i."')";
    	// mysqli_query($link, $query);
   		$i++;
    }
    $j = 0;
	//foreach ($stationArray['vagon'] as $vag){
    foreach ($stationArray['vagon'] as $st){
    	$id_station = SaveStation($st['name'], $st['code'], $link);
   		$id_side = SaveTrainSide($trains, $st['code'], $link);
    if(isset($id_side)){
    		$side_none = $id_side['side_none'];
            $side_left = $id_side['side_left'];
            $side_right = $id_side['side_right'];
           	$side_over = $id_side['side_over'];
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
        	$sept = $id_station['sept'];
    		$slag = $id_station['slag'];
        	$country = $id_station['country'];
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
            			"coal" => $coal,
            			"width" => $width,
            			"sept" => $sept,
            			"slag" => $slag,
            			"country" => $country
            );
        }

    	// $query = "INSERT INTO `rx_train_route` (`id`, `id_train`, `id_station`, `prib`, `wait`, `otpr`, `dist`, `day`, `vagon`, `diffTime`, `position`) VALUES (NULL, '".$id_train."', '".$id_station['id']."', '".$st['prib']."','".$st['wait']."','".$st['otpr']."','".$st['dist']."','".$st['day']."','1','".$st['diffTime']."', '".$j."')";
    	// mysqli_query($link, $query);
    $j++;
    }
    //$result_arr[] = $new_arr_vagon;
   // }
	if(isset($new_arr_station))	
    	$stationArray['stations'] = $new_arr_station;
	if(isset($new_arr_vagon))
    	$stationArray['vagon'] = $new_arr_vagon;
	if(isset($id_train))
	$stationArray["id_train"] = $id_train;
 	echo json_encode($stationArray);

}
function getSideStation($id_train, $link){
    $resultArr = array();
	$query = "SELECT * FROM `way_station` WHERE `code` = '".$id_train."'";
	//return $query;
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
   
           		$resultArr = $row;
            }
        return $resultArr;
        }
    
    }

}


function SaveTrainSide($trains, $id_station, $link){

	$query = "SELECT * FROM `way_trains` WHERE `number` = '".$trains."'";
//	return $query;
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
            	$resultArr = getSideStation($id_station, $link);
            }
        }
    	return $resultArr;
    }





// 	$query = "SELECT * FROM `rx_train_side` WHERE `train_name` = '".$trains."' AND `id_station` = '".$id_station."'";
//     $result = mysqli_query($link,$query);
// 	$count = mysqli_num_rows($result);
// if($result){
//     if($count > 0){
//     $id = array();
//         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//             $id = $row;
//         }
//     	return $id;
//     }else{
//     	$query = "INSERT INTO `rx_train_side`(`id`, `id_station`, `train_name`) VALUES (NULL,'".$id_station."','".$trains."')";
//         $result = mysqli_query($link,$query);
//     	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//             $id = $row;
//         }
//         //$id = mysqli_insert_id($link);
    	
//         return $id;
//     }
// }

}

function SaveStation($name, $code, $link){
	$query = "SELECT rx_station_info.*,station_list.country FROM `rx_station_info` LEFT JOIN `station_list` ON rx_station_info.code = station_list.code WHERE rx_station_info.code = '".$code."'";

    $result = mysqli_query($link,$query);
	$count = mysqli_num_rows($result);

if($result){
    if($count > 0){
    $id = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        }
        if($id['country'] == null){
        	if($code >= 5100013 && $code <= 5199136){
            	$country = 7;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else 
            if($code >= 5600020 && $code <= 5613880){
            	$country = 8;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 1000001 && $code <= 1000492){
            	$country = 9;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 4200300 && $code <= 4200550){
            	$country = 10;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 5300207 && $code <= 5399292){
            	$country = 12;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 5500009  && $code <= 5599001){
            	$country = 13;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
             if($code >= 2900001  && $code <= 2902205){
            	$country = 6;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }
            $id['country'] = $country;
        }
    	
    	return $id;
    }else{
    	$query_ = "SELECT `country` FROM `station_list` WHERE `code` = '".$code."'";
      
        $result_ = mysqli_query($link,$query_);
    	while ($row_ = mysqli_fetch_array($result_, MYSQLI_ASSOC)) {
            $country = $row['country'];
        }
    
    	if(!isset($country)){
        	if($code >= 5100013 && $code <= 5199136){
            	$country = 7;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else 
            if($code >= 5600020 && $code <= 5613880){
            	$country = 8;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 1000001 && $code <= 1000492){
            	$country = 9;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 4200300 && $code <= 4200550){
            	$country = 10;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 5300207 && $code <= 5399292){
            	$country = 12;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
            if($code >= 5500009  && $code <= 5599001){
            	$country = 13;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }else
             if($code >= 2900001  && $code <= 2902205){
            	$country = 6;
            	$query = "INSERT INTO `station_list` (`name`, `code`, `country`) VALUES ('".$name."', '".$code."', '".$country."')";
                mysqli_query($link,$query);
            }
            
        }
    
    
    
    	$query = "INSERT INTO `rx_station_info` (`id`, `name`, `code`, `type`, `add_user`) VALUES (NULL, '".$name."', '".$code."', 0, 0)";
        $result = mysqli_query($link,$query);
     	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row;
        	$id['country'] = $country;
        }
        //$id = mysqli_insert_id($link);
    	
        return $id;
    }
}

}


// закрываем подключение
mysqli_close($link);

?>