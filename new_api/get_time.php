<?php
header('Content-type: application/json');

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$time = $data['time'];

if($time != null){
$timestamp = time() - 3*60*60;
$timer = date('d.m.Y H:i:s', $timestamp);
	 $all_mass = array(  // Формируем массив
            'messege' => 'true',
     		'time' => $timer
        );
    echo json_encode($all_mass);
}

?>