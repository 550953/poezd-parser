<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
include '../simple_html_dom.php';

// require_once '../api/connection.php'; // подключаем скрипт
// // подключаемся к серверу
// $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
// mysqli_set_charset($link, "utf8");

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
 $trains = $_GET['trains'];
 $dateStart = $_GET['date'];
// $full_name = $_GET['full_name'];
//$trains = $data['trains'];
echo $trains;
$result_arr = ParseTrainInfo($trains,$dateStart);
print_r($result_arr);

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


?>