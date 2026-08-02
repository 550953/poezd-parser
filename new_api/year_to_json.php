<?php


error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '512M');


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$name = $data['name'];
$train = $data['train'];
$routesFile = file_get_contents('year_file.json');
$routesData = json_decode($routesFile, true);
if(isset($name)){
//print_r(GetNameList($routesData));
	echo json_encode(GetNameList($routesData));
}

if(isset($train)){
	//print_r(GetTrainList($routesData, $train, $full));
	echo json_encode(GetTrainList($routesData, $train));
}


function GetTrainList($routesData, $train, $full){
	$result = array();
	foreach($routesData as $value){
    	$number = $value['number'];
    	$schedule = $value['schedule'];
    	$title = $value['title'];
    $train = strtoupper($train);
 //   echo $train." - ".$number;
    	if($number == $train && $title == $full){
        	$result[] = array(
            	"number"=> $number,
            	"schedule" => $schedule
            );
        }
    	
    }
return $result;
}
	
function GetNameList($routesData){
	$result = array();
	foreach($routesData as $value){
    //	echo $value['number']." - ".$value['title']."<br>";
    	$result[] = $value['number'].", ".$value['title'];
    	$title = $value['title'];
    	$number = $value['number'];
    	$schedule = $value['schedule'];
    }
	//print_r($result);
	return $result;
	//echo json_encode($result);
}


?>