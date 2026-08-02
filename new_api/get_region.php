<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
ini_set('memory_limit', '1024M');
$to = $_GET['to'];
$from = 50;
$countryAll = array();
getStation($to,$from, $link);
// getPunkt($to,$from, $link);
//print_r($countryAll);

function getStation($to,$from,$link){
 	$query = "SELECT `name`,`code` FROM `station_list` WHERE `country` = 5  LIMIT ".$to.",".$from."";
//echo $query;
    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $name = $row['name'];
        	$code = $row['code'];
        	getNewRegion($name, $code, $link);
        }
    }
}

function getNewRegion($name, $code, $link){
    $url = 'https://www.xn--90aiim0b4c.xn--80aswg/api/v1/railway/stations?q='.urlencode($name);
	$json = file_get_contents($url);
	$obj = json_decode($json);
    foreach($obj as $train){
   		if(isset($train -> code)){
        	$excode = $train -> code;
            //echo $train -> code;
        }
   		if(isset($train -> country)){
        	$name = $train -> country;
        	$exname = $name->name;
        	//echo $name->name;
        }
    
    	if($code == $excode){
        	echo $exname." ";
        	saveToBase($excode, $exname, $link);
        }
    
    }
	//print_r($obj);
}



function getPunkt($to,$from,$link){
 	$query = "SELECT * FROM `station_list` WHERE `name` LIKE '%пункт%' AND `country` = 5";

    $result = mysqli_query($link,$query);
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $name = $row['name'];
        	$pieces = explode(",", $name);
            if(count($pieces) > 1){
            	$query_ = "UPDATE `station_list` SET `name`='".$pieces[0]."' WHERE `id`='".$row['id']."'";
            	mysqli_query($link,$query_);
                echo $pieces[0]." ";
            }
        	
     
    	//	mysqli_query($link,$query_);
        }
    }
}





function getRegions($name,$code, $link){
    $url = 'https://ticket.rzd.ru/api/v1/suggests?GroupResults=true&RailwaySortPriority=true&MergeSuburban=true&Query='.urlencode($name).'&Language=ru&TransportType=rail,suburban';
	$json = file_get_contents($url);
	$obj = json_decode($json);
	//print_r($obj);
	//echo count($obj->train);
	if(isset($obj->train)){
    	$trains = $obj->train;
    } 
	if(isset($obj->city)){
    	$sity = $obj->city;
    }
	if(isset($trains)){
    	$find = false;
    	foreach($trains as $train){
            if(isset($train -> expressCode)){
        	$expressCode = $train -> expressCode;
        	//echo $expressCode." ".$code." // ";
        	$region = $train -> region;
        	//echo $region." ";
        	if($code == $expressCode){
            	$find = true;
               
            	saveToBase($expressCode, $region, $link);
        	}
            }
    	}
    	if(!$find){
        	if(isset($sity)){
    			$find = false;
    			foreach($sity as $train){
        			$expressCode = $train -> expressCode;
        			//echo $expressCode." ".$code." // ";
        			$region = $train -> region;
        	//echo $region." ";
        			if($code == $expressCode){
            			$find = true;
                      
            			saveToBase($expressCode, $region, $link);
        			}
    			}
    			if(!$find){
        			//echo "Ни чего не найдено train to sity!";
                 
        			//updateBase(5, $code, $link);
    			}
    		}else{
        		//echo "Ни чего не найдено train to sity find!";
            	
        		//updateBase(5, $code, $link);
            }
        }
    }else if(isset($sity)){
    	$find = false;
    	foreach($sity as $train){
        if(isset($train -> expressCode)){
        	$expressCode = $train -> expressCode;
            
        	echo $expressCode." ".$code." // ";
        	$region = $train -> region;
        	//echo $region." ";
        	if($code == $expressCode){
            	$find = true;
               
            	saveToBase($expressCode, $region, $link);
        	}
        }
    	}
    	if(!$find){
        	//echo "Ни чего не найдено! sity";
           
        	//updateBase(5, $code, $link);
    	}
    }else{
    	//echo "Ни чего не найдено! not". $code. " ". $name. " ";
    	//updateBase(5, $code, $link);
    }
	
}

function saveToBase($expressCode, $region, $link){
	$pos = stripos($region, "Российская Федерация");
    $pos1 = stripos($region, "Казахстан");
	$pos2 = stripos($region, "Белоруссия");
	$pos3 = stripos($region, "Украина");
	$pos4 = stripos($region, "Молдова");
	$pos6 = stripos($region, "Узбекистан");
	$pos7 = stripos($region, "Польша");
	$pos8 = stripos($region, "Словацкая");
	$pos9 = stripos($region, "Финляндия");
	$pos10 = stripos($region, "Абхазия");
	$pos11 = stripos($region, "Австрия");
	$pos12 = stripos($region, "Румыния");
	$pos13 = stripos($region, "Венгрия");
	$pos14 = stripos($region, "Литва");
	$pos15 = stripos($region, "Германия");
	$pos16 = stripos($region, "Китай");
	$pos17 = stripos($region, "Вьетнам");
	$pos18 = stripos($region, "Швеция");
	$pos19 = stripos($region, "Греция");
	$pos20 = stripos($region, "Черногория");
	$pos21 = stripos($region, "Македония");
	$pos22 = stripos($region, "Чешская");
	$pos23 = stripos($region, "Румыния");
	$pos24 = stripos($region, "Сербия");
	$pos25 = stripos($region, "Болгария");
	$pos26 = stripos($region, "Туркменистан");
	$pos27 = stripos($region, "Словения");
	$pos28 = stripos($region, "Монголия");
	$pos29 = stripos($region, "Кыргызстан");
	$pos30 = stripos($region, "Таджикистан");
	$pos31 = stripos($region, "Латвия");
	$pos32 = stripos($region, "Грузия");
	$pos33 = stripos($region, "Азербайджан");
	$pos34 = stripos($region, "Швейцария");
	$pos35 = stripos($region, "Франция");
	$pos36 = stripos($region, "Дания");
	$pos37 = stripos($region, "Эстония");
	$pos38 = stripos($region, "Иран");
	$pos39 = stripos($region, "Италия");
$pos40 = stripos($region, "Хорватия");
$pos41 = stripos($region, "Люксембург");
$pos42 = stripos($region, "Северная Корея");
$pos43 = stripos($region, "Нидерланды");
$pos44 = stripos($region, "Великобритания");
$pos45 = stripos($region, "Бельгия");
$pos46 = stripos($region, "Турция");
$pos47 = stripos($region, "Норвегия");

	// $str = explode(",", $region);
	// $country = $str[count($str)-1];
	// echo $country." ";
	// $countryAll[] = $country;
    
// Конечно, 'a' не входит в 'xyz'
	if ($pos !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(0, $expressCode, $link);
	}else
	if ($pos1 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(1, $expressCode, $link);
	}else
	if ($pos2 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(2, $expressCode, $link);
	}else
	if ($pos3 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(3, $expressCode, $link);
	}else
	if ($pos4 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(4, $expressCode, $link);
	}else
    
	if ($pos6 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(6, $expressCode, $link);
	}
	else
	if ($pos7 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(7, $expressCode, $link);
	}
	else
	if ($pos8 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(8, $expressCode, $link);
	}
	else
	if ($pos9 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(9, $expressCode, $link);
	}
	else
	if ($pos10 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(10, $expressCode, $link);
	}
else
	if ($pos11 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(11, $expressCode, $link);
	}
else
	if ($pos12 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(12, $expressCode, $link);
	}
else
	if ($pos13 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(13, $expressCode, $link);
	}
else
	if ($pos14 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(14, $expressCode, $link);
	}
else
	if ($pos15 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(15, $expressCode, $link);
	}
else
	if ($pos16 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(16, $expressCode, $link);
	}
else
	if ($pos17 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(17, $expressCode, $link);
	}
else
	if ($pos18 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(18, $expressCode, $link);
	}
else
	if ($pos19 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(19, $expressCode, $link);
	}
else
	if ($pos20 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(20, $expressCode, $link);
	}
else
	if ($pos21 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(21, $expressCode, $link);
	}
else
	if ($pos22 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(22, $expressCode, $link);
	}
else
	if ($pos23 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(23, $expressCode, $link);
	}
else
	if ($pos24 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(24, $expressCode, $link);
	}
else
	if ($pos25 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(25, $expressCode, $link);
	}
else
	if ($pos26 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(26, $expressCode, $link);
	}
else
	if ($pos27 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(27, $expressCode, $link);
	}
else
	if ($pos28 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(28, $expressCode, $link);
	}
else
	if ($pos29 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(29, $expressCode, $link);
	}
else
	if ($pos30 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(30, $expressCode, $link);
	}
else
	if ($pos31 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(31, $expressCode, $link);
	}
else
	if ($pos32 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(32, $expressCode, $link);
	}
else
	if ($pos33 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(33, $expressCode, $link);
	}
else
	if ($pos34 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(34, $expressCode, $link);
	}
else
	if ($pos35 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(35, $expressCode, $link);
	}
else
	if ($pos36 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(36, $expressCode, $link);
	}
else
	if ($pos37 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(37, $expressCode, $link);
	}
else
	if ($pos38 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(38, $expressCode, $link);
	}
else
	if ($pos39 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(39, $expressCode, $link);
	}
else
	if ($pos40 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(40, $expressCode, $link);
	}
else
	if ($pos41 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(41, $expressCode, $link);
	}
else
	if ($pos42 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(42, $expressCode, $link);
	}
else
	if ($pos43 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(43, $expressCode, $link);
	}
else
	if ($pos44 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(44, $expressCode, $link);
	}
else
	if ($pos45 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(45, $expressCode, $link);
	}


else
	if ($pos46 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(46, $expressCode, $link);
	}


else
	if ($pos47 !== false) {
    	//echo "Строка '$region'  найдена ";
    	updateBase(47, $expressCode, $link);
	}



	else{
    	updateBase(5, $expressCode, $link);
    }
	

}
function updateBase($info, $code, $link){
	$query = "UPDATE `station_list` SET `country`='".$info."' WHERE `code`='".$code."'";
	//echo $query;
    $result = mysqli_query($link,$query);
}
// echo count($codeStation)." size";
// echo "<pre>";
// print_r($codeStation);
// echo "<pre>";
?>