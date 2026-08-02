<?php



error_reporting(E_ALL);
ini_set("display_errors", 1);

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//$code0 = $data['code0'];
//$code1 = $data['code1'];
//$date = $data['date'];

$code0 = $_GET['code0'];
$code1 = $_GET['code1'];
$date = $_GET['date'];
if($code0 != null && $code1 != null && $date != null){
    ParseTrain($code0, $code1, $date);
}

function ParseTrain($code0, $code1, $date)
{
    include '../../examples/get_train.php';
    get_train($code0, $code1, $date);
    echo json_encode(get_train($code0, $code1, $date));
}