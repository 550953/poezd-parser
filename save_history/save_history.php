<?php
require_once __DIR__ . '/../api/BsLogger.php';
$_bs_t = microtime(true);
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
if(isset($_POST['name']) && $_POST['name'] != null){
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/save_history/local_history/';

	$name = $_POST['name'];
	$uploadfile = $uploaddir . basename($_FILES[$name]['name']);


	if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
    	echo "Файл корректен и был успешно загружен.\n";
	} else {
    	echo "Возможная атака с помощью файловой загрузки!\n";
	}
}
if(isset($_POST['uuid']) && $_POST['uuid'] != null){
$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/save_history/local_history/';
 if (file_exists($uploaddir.$_POST['uuid'].".txt")) {
            echo "YES";
        }else {
            echo "NOT";
        }
}

if(isset($_POST['uuid_ios']) && $_POST['uuid_ios'] != null){
$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/save_history/local_history_ios/';
 if (file_exists($uploaddir.$_POST['uuid_ios'])) {
           echo json_encode( array('messege' => 'true'));
        }else {
            echo json_encode( array('messege' => 'false'));
        }
}


if(isset($_POST['name_ios']) && $_POST['name_ios'] != null){
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/save_history/local_history_ios/';
if(isset($_POST['name_ios'])){
	$name = $_POST['name_ios'];
if(isset($_FILES[$name])){
	$uploadfile = $uploaddir . basename($_FILES[$name]['name']);
if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
    	//echo json_encode( array('messege' => 'true'));
    	echo json_encode( array('messege' => 'true'));
	} else {
    	//echo json_encode($result);
    	//echo json_encode( array('messege' => $uploadfile));
    	echo json_encode( array('messege' => 'false'));
	}
}
}

 // if ( !file_exists($uploaddir) ) {
 //     mkdir ($dir, 0744);
 // }
    
	
}


// BsLogger request log
$_bs_action = null;
if     (isset($_POST["name"]))     $_bs_action = "upload_android";
elseif (isset($_POST["name_ios"])) $_bs_action = "upload_ios";
elseif (isset($_POST["uuid"]))     $_bs_action = "check_android";
elseif (isset($_POST["uuid_ios"])) $_bs_action = "check_ios";
elseif (isset($_POST["get_ios"]))  $_bs_action = "get_ios";
elseif (isset($_POST["get_new"]))  $_bs_action = "get_new";
BsLogger::request("/api/poezd/parse/save_history/save_history.php", 200, round((microtime(true) - $_bs_t) * 1000, 2));
BsLogger::event("info", "save_history", $_bs_action ?? "unknown", ["duration_ms" => round((microtime(true) - $_bs_t) * 1000, 2)]);
