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

$train = $data['train'];
$codeFrom = $data['codeFrom'];
$codeTo = $data['codeTo'];
$date_start = $data['date'];
$timeOtpr = $data['timeOtpr'];

 //$train = $_GET['train'];
// $codeFrom = $_GET['codeFrom'];
// $codeTo = $_GET['codeTo'];
// $date_start = $_GET['date'];
// $timeOtpr = $_GET['timeOtpr'];

if($train != null){
	$train = urlencode($train);
    $time = urlencode($date_start."T".$timeOtpr.":00");
$array_carr = ParseTrainInfo($train, $codeFrom, $codeTo, $time);
echo json_encode(array("data" => $array_carr));
}


function ParseTrainInfo($train, $codeFrom, $codeTo, $time)
{
  //  $trains = urlencode($trains);
    
    
    $mar = "https://bilet.railways.kz/sale/default/car/search?car_search_form%5BdepartureStation%5D=".$codeFrom."&car_search_form%5BarrivalStation%5D=".$codeTo."&car_search_form%5BforwardDirection%5D%5BdepartureTime%5D=".$time."&car_search_form%5BforwardDirection%5D%5Btrain%5D=".$train;
   // $mar="https://bilet.railways.kz/sale/default/car/search?car_search_form%5BdepartureStation%5D=2060150&car_search_form%5BarrivalStation%5D=2000000&car_search_form%5BforwardDirection%5D%5BdepartureTime%5D=2022-06-05T16%3A40%3A00&car_search_form%5BforwardDirection%5D%5BfluentDeparture%5D=&car_search_form%5BforwardDirection%5D%5Btrain%5D=025%D0%93&car_search_form%5BforwardDirection%5D%5BisObligativeElReg%5D=1&car_search_form%5BbackwardDirection%5D%5BdepartureTime%5D=&car_search_form%5BbackwardDirection%5D%5BfluentDeparture%5D=&car_search_form%5BbackwardDirection%5D%5Btrain%5D=&car_search_form%5BbackwardDirection%5D%5BisObligativeElReg%5D=";

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

       
        $box = $html->find('div[class="scroll-block"]');
     //   if(isset($html)){
        
    	$table = $html->find('table[class="ui single line structured unstackable table"]',0);
    	if(!isset($table))
        	return;
    	$table = $table -> find('tr');
    	
    	foreach($table as $row){
        	
        	$b = $row-> class;
        
        	if($b == "title"){
                $title = $row -> find('td',0);
            	$title = $title->plaintext;
            	$title = mb_substr($title, 0, 2);
            	
            }
        	if($b == "content carItem"){
        		if(isset($row)){
            // echo 'test';
        	 	$elCars = $row-> find('td');
            
            		foreach($elCars as $car){
           
            			$cabins = $car-> find('div[class="cabin"]');
                    	
              // echo count($cabins);
            			$seats = array();
            			$free = 0;
                    	$cabins = $car-> find('div[class="cabin"]');
            			foreach($cabins as $cabin){
                			$i = 0;
                
                			while($item = $cabin->children($i++)){
  								//echo $item->find('label[class="label"]',0)->plaintext;
                            	if(isset($item) && (is_object($item->find('input[class="places hidden"]',0))))
                            		$busy = $item->find('input[class="places hidden"]',0)->class;
                            	//	print_r($item);
                            	$b = $item-> class;
                            	

                           	 	if($b == "seat ui checkbox bl reserved disabled" || $b == "seat ui checkbox tl reserved disabled" || $b == "seat ui checkbox tr reserved disabled" || $b == "seat ui checkbox br reserved disabled"){
                                	
                                }else{
                                	$free = $free + 1;
                                	$seat = $item->find('label[class="label"]',0)->plaintext;
                                	$seats[] = $seat;
                                }

                            	
                            	$info = array(
                                	'seats'=> $seat,
                                	'free' => $free
                            	);
                    			
                	
							}  ///while
                        
                
                }  //foreach $cabins
                    $half_cabins = $car-> find('div[class="half-cabin"]');
            			foreach($half_cabins as $cabin){
                			$i = 0;
                
                			while($item = $cabin->children($i++)){
  								//echo $item->find('label[class="label"]',0)->plaintext;
                            if(isset($item) && (is_object($item->find('input[class="places hidden"]',0))))
                            	$busy = $item->find('input[class="places hidden"]',0)->class;
                            	//	print_r($item);
                            	$b = $item-> class;
                            	

                           	 	if($b == "seat ui checkbox bl reserved disabled" || $b == "seat ui checkbox tl reserved disabled" || $b == "seat ui checkbox tr reserved disabled" || $b == "seat ui checkbox br reserved disabled"){
                                	
                                }else{
                                	$free = $free + 1;
                                	$seat = $item->find('label[class="label"]',0)->plaintext;
                                	$seats[] = $seat;
                                }

                            	
                            	$info = array(
                                	'seats'=> $seat,
                                	'free' => $free
                            	);
                    			
                	
							}  ///while
                        
                
                }  //foreach $cabins
                    $results = addArr($results, $title, $seats, $free);
              // $results[] = array(
              //                   	'number'=> $title,
              // 						'free' => $free,
              //                   	'seats' => $seats
              //               	); 
                    $title = "";
                    $seats = array();
             //print_r($seats);
            	// print_r($el);
            }  //foreach $elCars
                
    	}  //if
            
        } //foreach $table 
         
			

    	
    }
    	 
    }
    //print_r($results);
//    $mass = array();
	
// 	for ($i = 0; $i < count($results); $i++){
//     $rec = true;
//     	$result = $results[$i];
   
//     	for ($j = 0; $j < count($results); $j++){
//         	$resultj = $results[$j];
//         	if($i != $j){
//             	if($result['number'] == $resultj['number']){
//                 	$result['free'] = $result['free'] + $resultj['free'];
//                 	//echo "w ".count($result['seats'])."  ".count($resultj['seats'])."  | ";
//                 	$result['seats'] = array_merge($result['seats'] , $resultj['seats'] );
//                 	$mass[] = $result;
//                 	echo $result['number']." NUM ";
//                 	break;
                
//                 $rec = false;
//                 }
//             }
//         }
//     //if($rec)
//     	//$mass[] = $result;
    	
//     }
   
	return $results;
   // return $result_date;

}
function addArr($arr, $number, $seats, $free){
	$rec = false;
	$i = 0;
	foreach($arr as $value){
   // echo "ADD ".$i." ".$number." ";
    	if($value['number'] == $number){
        	$value['free'] = $value['free'] + $free;
        	$value['seats'] = array_merge($value['seats'] , $seats);
            sort($value['seats']);
        	$arr[$i] = $value;
        	$rec = true;
        }
    	$i++;
    }
	if(!$rec && $free != 0)
    	$arr[] = array(
    						'number'=> $number,
              				'free' => $free,
                            'seats' => $seats
                       ); 
	return $arr;
}
function cleanArray($arr){
	$oldValue = null;
    $result = array();
	$i = 0;
	foreach($arr as $value){
    	if(strcmp($value['number'], $oldValue['number']) == 0){
        	$oldValue['seats'] = array_merge($oldValue['seats'], $value['seats']);
        	$oldValue['free'] = $oldValue['free'] + $value['free'];
            $oldValue['free']  = asort($oldValue['free']);
            
            $result[$i - 1] = $oldValue;
        }else{
        	$result[] = $value;
        }
    $oldValue = $value;
    $i++;
	}
	return $result;
}

// закрываем подключение
mysqli_close($link);
?>