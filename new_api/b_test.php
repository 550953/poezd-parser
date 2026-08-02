<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
//ini_set('memory_limit', '1024M');

$query = "SELECT * FROM `express_stations` LIMIT 4800,150"; 
$result = mysqli_query($link, $query);
//$count = mysqli_num_rows($result);	
$names = array();
if($result){
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$name = $row['name'];
    	echo $name;
    	$id = $row['id'];
       // $names[] = $name;
    	//$liter = substr($name, 0, -3);
	$liter = urlencode($name);
	$json = file_get_contents('https://ticket.rzd.ru/api/v1/suggests?GroupResults=true&RailwaySortPriority=true&MergeSuburban=true&Query='.$liter.'&Language=ru&TransportType=rail,suburban,avia,boat,bus,aeroexpress');
	$obj = json_decode($json);
	if(isset($obj->train)){
		$trains = $obj->train;
	    $j = 0;
		foreach($trains as $station){
			$name = $station->name;
			if(isset($station->expressCode)){
				$code = $station->expressCode;	
                $region = explode(", ", $station->region);
                if(count($region) > 3){
                	if(count($region) == 4)
                		$gmt = $region[2];
            		else
                		$gmt = $region[1];
            		echo getGMT($gmt);
                	if($j == 0){
                		$query1 = "UPDATE `express_stations` SET `gmt` = '".getGMT($gmt)."' WHERE `express_stations`.`id` = ".$id.""; 
						$result1 = mysqli_query($link, $query1);
                	}
                }
    			$object = new stdClass();
				$object->name = $name;
				$object->code = $code;
        		$codeStation[] = $object;
			}
        $j++;
		}
    }
    
    
    
}
$codeStation = array();
// foreach($names as $liter){
// 	$liter = substr($liter, 0, -3);
// 	$liter = urlencode($liter);
// 	$json = file_get_contents('https://ticket.rzd.ru/api/v1/suggests?GroupResults=true&RailwaySortPriority=true&MergeSuburban=true&Query='.$liter.'&Language=ru&TransportType=rail,suburban,avia,boat,bus,aeroexpress');
// 	$obj = json_decode($json);
// 	if(isset($obj->train)){
// 		$trains = $obj->train;
	
// 		foreach($trains as $station){
// 			$name = $station->name;
// 			if(isset($station->expressCode)){
// 				$code = $station->expressCode;
    			
//                 $region = explode(", ", $station->region);
//                 echo $region[1];
//             	echo getGMT($region[1]);
//     			$object = new stdClass();
// 				$object->name = $name;
// 				$object->code = $code;
//         		$codeStation[] = $object;
// 			}
// 		}
//     }
// }



// $liter = "Баксан";
// $liter = urlencode($liter);
// 	$json = file_get_contents('https://ticket.rzd.ru/api/v1/suggests?GroupResults=true&RailwaySortPriority=true&MergeSuburban=true&Query='.$liter.'&Language=ru&TransportType=rail,suburban,avia,boat,bus,aeroexpress');
// 	$obj = json_decode($json);
// 	if(isset($obj->train)){
// 		$trains = $obj->train;
	
// 		foreach($trains as $station){
// 			$name = $station->name;
// 			if(isset($station->expressCode)){
// 				$code = $station->expressCode;
    			
//                 $region = explode(", ", $station->region);
//             	if(count($region) == 3)
//                 	echo $region[1];
//             	else
//                 	echo $region[2];
//             	echo getGMT($region[1]);
//     			$object = new stdClass();
// 				$object->name = $name;
// 				$object->code = $code;
//         		$codeStation[] = $object;
// 			}
// 		}
//     }

// for($i = 0; $i < count($codeStation); $i++){
// 	$object = $codeStation[$i];
//     saveToBase($object, $link);
// }



}

function getGMT($region){
$result = 0;
	switch ($region) {
   
    	case "Архангельская Область":
        	$result = 0;
        	break;
    case "Белгородская Область":
        	$result = 0;
        	break;
    case "Брянская Область":
        	$result = 0;
        	break;
    case "Владимирская Область":
        	$result = 0;
        	break;
    case "Волгоградская Область":
        	$result = 0;
        	break;
    case "Воронежская Область":
        	$result = 0;
        	break;
    case "Ивановская Область":
        	$result = 0;
        	break;
    case "Кабардино-Балкарская Республика":
        	$result = 0;
        	break;
    case "Калужская Область":
        	$result = 0;
        	break;
    case "Карачаево-Черкесская Республика":
        	$result = 0;
        	break;
    case "Кировская Область":
        	$result = 0;
        	break;
    case "Костромская Область":
        	$result = 0;
        	break;
    case "Краснодарский Край":
        	$result = 0;
        	break;
    case "Курская Область":
        	$result = 0;
        	break;
    case "Ленинградская Область":
        	$result = 0;
        	break;
    case "Липецкая Область":
        	$result = 0;
        	break;
    case "Московская Область":
        	$result = 0;
        	break;
    case "Мурманская Область":
        	$result = 0;
        	break;
    case "Ненецкий автономный округ":
        	$result = 0;
        	break;
    case "Нижегородская Область":
        	$result = 0;
        	break;
     case "Новгородская Область":
        	$result = 0;
        	break;
     case "Орловская Область":
        	$result = 0;
        	break;
     case "Пензенская Область":
        	$result = 0;
        	break;
     case "Псковская Область":
        	$result = 0;
        	break;
     case "Адыгея Республика":
        	$result = 0;
        	break;
     case "Дагестан Республика":
        	$result = 0;
        	break;
     case "Ингушетия Республика":
        	$result = 0;
        	break;
     case "Калмыкия Республика":
        	$result = 0;
        	break;
    case "Карелия Республика":
        	$result = 0;
        	break;
     case "Коми Республика":
        	$result = 0;
        	break;
    case "Крым Республика":
        	$result = 0;
        	break;
     case "Марий Эл Республика":
        	$result = 0;
        	break;
    case "Мордовия Республика":
        	$result = 0;
        	break;
     case "Северная Осетия Республика":
        	$result = 0;
        	break;
    case "Татарстан Республика":
        	$result = 0;
        	break;
     case "Ростовская Область":
        	$result = 0;
        	break;
     case "Рязанская Область":
        	$result = 0;
        	break;
     case "Саратовская Область":
        	$result = 0;
        	break;
     case "Смоленская Область":
        	$result = 0;
        	break;
     case "Ставропольский Край":
        	$result = 0;
        	break;
     case "Тамбовская Область":
        	$result = 0;
        	break;
     case "Тверская Область":
        	$result = 0;
        	break;
     case "Тульская Область":
        	$result = 0;
        	break;
    case "Чеченская Республика":
        	$result = 0;
        	break;
    case "Чувашская Республика - Чувашия":
        	$result = 0;
        	break;
    case "Ярославская Область":
        	$result = 0;
        	break;
    case "Калининградская Область":
        	$result = -1;
        	break;
    case "Астраханская Область":
        	$result = 1;
        	break;
    case "Самарская Область":
        	$result = 1;
        	break;
    case "Удмуртская Республика":
        	$result = 1;
        	break;
     case "Ульяновская Область":
        	$result = 1;
        	break;
     case "Курганская Область":
        	$result = 2;
        	break;
     case "Оренбургская Область":
        	$result = 2;
        	break;
     case "Пермская Область":
        	$result = 2;
        	break;
     case "Башкортостан Республика":
        	$result = 2;
        	break;
     case "Свердловская Область":
        	$result = 2;
        	break;
     case "Тюменская Область":
        	$result = 2;
        	break;
     case "Ханты-Мансийский Автономный округ - Югра Автономный округ":
        	$result = 2;
        	break;
     case "Челябинская Область":
        	$result = 2;
        	break;
   	case "Ямало-Ненецкий автономный округ":
        	$result = 2;
        	break;
    case "Омская Область":
        	$result = 3;
        	break;
    case "Алтайский Край":
        	$result = 4;
        	break;
    case "Красноярский Край":
        	$result = 4;
        	break;
    case "Кемеровская Область":
        	$result = 4;
        	break;
    case "Новосибирская Область":
        	$result = 4;
        	break;
    case "Республика Алтай":
        	$result = 4;
        	break;
     case "Республика Тыва":
        	$result = 4;
        	break;
     case "Республика Хакасия":
        	$result = 4;
        	break;
     case "Томская Область":
        	$result = 4;
        	break;
     case "Иркутская Область":
        	$result = 5;
        	break;
     case "Республика Бурятия":
        	$result = 5;
        	break;
     case "Амурская Область":
        	$result = 6;
        	break;
     case "Забайкальский Край":
        	$result = 6;
        	break;
     case "Республика Саха-Якутия":
        	$result = 6;
        	break;
     case "Еврейская Авт. обл.":
        	$result = 7;
        	break;
     case "Приморский Край":
        	$result = 7;
        	break;
    case "Хабаровский Край":
        	$result = 7;
        	break;
     case "Магаданская Область":
        	$result = 7;
        	break;
    case "Сахалинская Область":
        	$result = 8;
        	break;
     case "Камчатский Край":
        	$result = 9;
        	break;
    case "Чукотский Автономный Округ":
        	$result = 9;
        	break;
	}
	return $result;
}
function saveToBase($obj, $link){

	$query = "SELECT * FROM `express_stations` WHERE `code`='".$obj->code."'"; 
    $result = mysqli_query($link, $query);
    if($result){
    $count = mysqli_num_rows($result);
	
    	if($count == 0){
        	if($result){
            	$query = "INSERT INTO `express_stations` (`id`, `name`, `code`) VALUES (NULL,'".$obj->name."','".$obj->code."')";
        		mysqli_query($link, $query);
        	}
    	}
    }

}





        
    

echo "<pre>";
print_r($codeStation);
echo "<pre>";
//echo count($obj->countries);
// $country = $obj->countries;
// $russia = array();
// foreach($country as $regions){
// 	//echo $region->title;
// 	if($regions->title == "Россия"){
//     $russia = $regions;
// 	}
// }
// $settlements = array();
?>