<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");


	$query = "SELECT * FROM `rx_station_info`  WHERE `buffer` != ''";
//$query = "SELECT * FROM `rx_station_info`  WHERE `id` = '49'";
//echo $query;
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$old_buffer = $row['buffer'];
            	$old_comment = $row['comment'];
            	$comment = $row['comment'];
          
            $new_buff = str_replace(" | ", "", $old_buffer);
            
        //    $new_buff = str_replace(".", "", $new_buff);
            //$old_comment = str_replace(" ", "", $old_comment);
            
          //  $old_comment = str_replace(".", "", $old_comment);
            $buf_arr = explode("\n", $new_buff);
            $com_arr = explode("\n", $old_comment);
            $clear = true;
            $record = false;
            foreach ($buf_arr as  $buf_sword) {
            $buf_sword = preg_replace('/\W+/u', '', $buf_sword);
            	$record = false;
    			foreach ($com_arr as $com_sword) {
                	$com_sword = preg_replace('/\W+/u', '', $com_sword);
                	if ($com_sword==$buf_sword){
                    	//echo $com_sword."  ".$buf_sword."\n";
                    	$record = true;
                    	break;
                    }
                // else{
                // 		echo "1- ".$com_sword."  ".$buf_sword." ".strlen($com_sword)." ".strlen($buf_sword)."\n";
                // 	}
				}
            	if(!$record){
                	//echo "2- ".$com_sword."  ".$buf_sword."\n";
                	$clear = false;
            	}
			}
          	if($clear && $old_comment != ""){
            	//echo "CLEAN  ".$row['id']." \n";
          		$comment  = "";
          		$query1 = "UPDATE `rx_station_info` SET  `buffer` = '".$comment."' WHERE `id` = '".$row['id']."'";
				mysqli_query($link, $query1);
           }
            	
           
           }
        
        }
    }

?>