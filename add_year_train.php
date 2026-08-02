<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
$host = '127.0.0.1'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'K2ClMv77SQT3gF3k'; // пароль

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '256M');

$res = getWay($link, 1,1);
//print_r($res);

foreach($res as $value){
echo count($value['dates'])." ".$value['number']."<pre>";
$as = array("dates"=>$value['dates']);
$results = json_encode($as);
//print_r($results);
	saveToServer($value['number'], $results, $link);
}

function getWay($link, $start, $count){
	$arraylistSort = array();
	$query = "SELECT * FROM `aw_train_list` LIMIT ".$start.",".$count."";
	$result = mysqli_query($link, $query);
  //echo $query;
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    		$arr_st = $row['arr_station'];
			$dep_st = $row['dep_station'];
			$arr_code = $row['arr_code'];
			$dep_code = $row['dep_code'];
        
       		$url = "https://api.rasp.yandex.net/v3.0/search/?apikey=d7b4a17b-32ba-4e77-9599-c29528ca1510&from=".$dep_code."&to=".$arr_code."&format=json&lang=ru_RU&transport_types=train&system=yandex&show_systems=esr&offset=0&limit=500&transfers=false&add_days_mask=true";
    		echo $url;
        $json = file_get_contents($url);
        
			$object = json_decode($json, true);
       		//print_r($object);
        	$segments = $object['segments'];
        	$add_row = true;
        	$number_old = "";
        	// print_r($segments);
        $j = 0;
        		foreach($segments as $value){
                	
            		$number = $value['thread']['number'];
                 	
            		$firthDate = null;
            		$minDate = null;
        			$result_date = array();
       	
        		//$schedule = $value->('schedule');
            		
            			$shedule = $value['schedule'];
            			foreach($shedule as $val){
            				$year = $val['year'];
            				$month = $val['month'];
            				$days = $val['days'];
            				$i = 1;
            				foreach($days as $day){
                	//print_r($day);
                				if($day == 1){
                					$rain = true;
                    			}else{
                    				$rain = false;
                    			}
                				$date = $i.".".$month.".".$year;
                	
                	//echo $rain." ".$date;
                				$result_date[] = array(
                        			"date" => $date,
                        			"value" => $rain
                    			);
               
                       //Log.d(TAG, "rain " + rain + " " + date);
                				$i++;
                			}
            //	print_r($val['year']);
            			}
                  //  $result_date = array("dates" => $result_date);
                	$add = array("number" => $number,
                                 "dates"  => $result_date
                                );
                
               	$arraylistSort = addObject($add, $arraylistSort);
                   // $results = $result_date;
        		//$results = json_encode($arraylistSort);
        		//echo $number;
        		//echo $results;
                // $arr = array("number" => $number,
                //             "dates" =>$results);
                
                
                    
                $j++;
           		 	
            //echo $year;
        		 
        		}
       	
    	}
	}
	return $arraylistSort;
}

function addObject($newObj, $arraylistSort)
{
    $found = false;
	$add = array();
	//print_r($arraylistSort);
    foreach ($arraylistSort as $obj) {
        if ($obj['number'] === $newObj['number']) {
        echo count($obj['dates'])."---".count($newObj['dates'])."<pre>";
            // Объединяем и удаляем дубликаты с сохранением порядка
       		//print_r($obj['dates']);
            $combined = array_merge($obj['dates'], $newObj['dates']);
//             $seen = [];
//             $unique = [];

//             foreach ($combined as $dateWay) {
//                 $hash = serialize($dateWay);
//                 if (!isset($seen[$hash])) {
//                     $seen[$hash] = true;
//                     $unique[] = $dateWay;
//                 }
//             }
			
            $obj['dates'] = $combined;
        	$add[] = array("number" => $obj['number'],
                                 "dates"  => $obj['dates']
                                );
        	
            $found = true;
        	//echo count($obj['dates'])."---==".count($newObj['dates'])."<pre>";
           // break;
        
        }
    $add[] = $obj;
    }
if (!$found) {
    $add[] = $newObj;
       // $arraylistSort[] = $newObj;
    }

    
 foreach ($add as $obj) {
  echo count($obj['dates'])."-++-".$newObj['number']."<pre>";
 }
	return $add;
}
function saveToServer($number, $results, $link){

	

	$query = "SELECT * FROM `year_train` WHERE `number` = '".$number."'";
	$result = mysqli_query($link, $query);
	$row_cnt = mysqli_num_rows($result);
$serialized_array = serialize($results); 
	if($row_cnt > 0){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {	
        	$qw = "UPDATE `year_train` SET `text`='".$serialized_array."',`date_update`= now() WHERE `id`= '".$row['id']."'";
        	mysqli_query($link, $qw);
        	//echo $qw;
        }
	}else{
    	$qw = "INSERT INTO `year_train`(`id`, `number`, `text`, `date_update`) VALUES (NULL,'".$number."','".$serialized_array."',NOW())";
    	mysqli_query($link, $qw);
   	 	//echo $qw;
    }

}




$train = '[
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9608904",
    "departure_station": "Абакан"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9608904",
    "departure_station": "Абакан"
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s9608904",
    "departure_station": "Абакан"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9608904",
    "departure_station": "Абакан"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9612982",
    "arrival_station": "Владикавказ",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9612402",
    "arrival_station": "Киров-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s2000007",
    "arrival_station": "Москва (Киевский вокзал)",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9612913",
    "arrival_station": "Ростов-Главный",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9606096",
    "arrival_station": "Самара",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9613524",
    "arrival_station": "Ставрополь",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9613054",
    "departure_station": "Адлер"
  },
  {
    "arrival_code": "s9607594",
    "arrival_station": "Сосьва-новая",
    "departure_code": "s9607563",
    "departure_station": "Алапаевск"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610364",
    "departure_station": "Алейская"
  },
  {
    "arrival_code": "s9882415",
    "arrival_station": "Аэропорт Сочи",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s2000007",
    "arrival_station": "Москва (Киевский вокзал)",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s9613483",
    "arrival_station": "Таганрог (старый вокзал)",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9613091",
    "departure_station": "Анапа"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9603962",
    "arrival_station": "Вологда-1",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9605047",
    "arrival_station": "Грязи-Воронежские",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9604093",
    "arrival_station": "Карпогоры-Пасс.",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9604083",
    "arrival_station": "Котлас-Южный",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9602693",
    "arrival_station": "Мурманск",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9604152",
    "arrival_station": "Обозерская",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s2010400",
    "departure_station": "Архангельск-Город"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605765",
    "departure_station": "Астрахань-1"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9605765",
    "departure_station": "Астрахань-1"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9605765",
    "departure_station": "Астрахань-1"
  },
  {
    "arrival_code": "s9608833",
    "arrival_station": "Лесосибирск",
    "departure_code": "s9608884",
    "departure_station": "Ачинск-1"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9882415",
    "departure_station": "Аэропорт Сочи"
  },
  {
    "arrival_code": "s9613602",
    "arrival_station": "Краснодар-1",
    "departure_code": "s9882415",
    "departure_station": "Аэропорт Сочи"
  },
  {
    "arrival_code": "s9612913",
    "arrival_station": "Ростов-Главный",
    "departure_code": "s9882415",
    "departure_station": "Аэропорт Сочи"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605909",
    "departure_station": "Балаково"
  },
  {
    "arrival_code": "s9613990",
    "arrival_station": "Минск (Институт Культуры)",
    "departure_code": "s9613998",
    "departure_station": "Барановичи-Полесские"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9613998",
    "departure_station": "Барановичи-Полесские"
  },
  {
    "arrival_code": "s9614043",
    "arrival_station": "Слуцк",
    "departure_code": "s9613998",
    "departure_station": "Барановичи-Полесские"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610364",
    "arrival_station": "Алейская",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610404",
    "arrival_station": "Бийск",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610519",
    "arrival_station": "Ребриха",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610493",
    "arrival_station": "Рубцовск",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9611402",
    "arrival_station": "Северобайкальск",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610419",
    "arrival_station": "Славгород",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9610503",
    "arrival_station": "Татарская",
    "departure_code": "s9610483",
    "departure_station": "Барнаул"
  },
  {
    "arrival_code": "s9606216",
    "arrival_station": "Пенза-1",
    "departure_code": "s9606601",
    "departure_station": "Башмаково"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9605027",
    "departure_station": "Белгород"
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s9611008",
    "departure_station": "Белогорск"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9607338",
    "departure_station": "Белый Яр"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610404",
    "departure_station": "Бийск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9610404",
    "departure_station": "Бийск"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9610404",
    "departure_station": "Бийск"
  },
  {
    "arrival_code": "s9608904",
    "arrival_station": "Абакан",
    "departure_code": "s9608843",
    "departure_station": "Бискамжа"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9872294",
    "arrival_station": "Нижний Бестях",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9610789",
    "arrival_station": "Сети",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s9611007",
    "departure_station": "Благовещенск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607632",
    "departure_station": "Богданович"
  },
  {
    "arrival_code": "s9611006",
    "arrival_station": "Забайкальск",
    "departure_code": "s9611027",
    "departure_station": "Борзя"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9614023",
    "departure_station": "Брест-Центр."
  },
  {
    "arrival_code": "s2000006",
    "arrival_station": "Москва (Белорусский вокзал)",
    "departure_code": "s9614023",
    "departure_station": "Брест-Центр."
  },
  {
    "arrival_code": "s9602496",
    "arrival_station": "Санкт-Петербург (Витебский вокзал)",
    "departure_code": "s9614023",
    "departure_station": "Брест-Центр."
  },
  {
    "arrival_code": "s2000007",
    "arrival_station": "Москва (Киевский вокзал)",
    "departure_code": "s9600826",
    "departure_station": "Брянск-1-Орловский"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9600826",
    "departure_station": "Брянск-1-Орловский"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9609329",
    "departure_station": "Бузулук"
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s2014360",
    "departure_station": "Валуйки"
  },
  {
    "arrival_code": "s2000006",
    "arrival_station": "Москва (Белорусский вокзал)",
    "departure_code": "s9603033",
    "departure_station": "Великие Луки"
  },
  {
    "arrival_code": "s9602496",
    "arrival_station": "Санкт-Петербург (Витебский вокзал)",
    "departure_code": "s9603033",
    "departure_station": "Великие Луки"
  },
  {
    "arrival_code": "s2006004",
    "arrival_station": "Москва (Ленинградский вокзал)",
    "departure_code": "s9602893",
    "departure_station": "Великий Новгород"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9609452",
    "departure_station": "Верхний Уфалей"
  },
  {
    "arrival_code": "s9601547",
    "arrival_station": "Александров-1",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9608418",
    "arrival_station": "Ванино",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9872294",
    "arrival_station": "Нижний Бестях",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9608554",
    "arrival_station": "Ружино",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9608543",
    "arrival_station": "Советская Гавань-Сорт.",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9608404",
    "departure_station": "Владивосток"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9612982",
    "departure_station": "Владикавказ"
  },
  {
    "arrival_code": "s9613073",
    "arrival_station": "Минеральные Воды",
    "departure_code": "s9612982",
    "departure_station": "Владикавказ"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9612982",
    "departure_station": "Владикавказ"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9612982",
    "departure_station": "Владикавказ"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9612982",
    "departure_station": "Владикавказ"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s2020500",
    "departure_station": "Волгоград-1"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s2020500",
    "departure_station": "Волгоград-1"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s2020500",
    "departure_station": "Волгоград-1"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9603962",
    "departure_station": "Вологда-1"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9603962",
    "departure_station": "Вологда-1"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9603962",
    "departure_station": "Вологда-1"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s9604103",
    "arrival_station": "Лабытнанги",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s9604201",
    "arrival_station": "Сыктывкар",
    "departure_code": "s9603971",
    "departure_station": "Воркута"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s2014001",
    "departure_station": "Воронеж-1"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s2014001",
    "departure_station": "Воронеж-1"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9608517",
    "departure_station": "Вяземская"
  },
  {
    "arrival_code": "s9613446",
    "arrival_station": "Шептуховка",
    "departure_code": "s9611798",
    "departure_station": "Глубокая"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9614088",
    "departure_station": "Гомель-Пасс."
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9614058",
    "departure_station": "Гродно"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613083",
    "departure_station": "Грозный"
  },
  {
    "arrival_code": "s9607632",
    "arrival_station": "Богданович",
    "departure_code": "s9612957",
    "departure_station": "Дербент"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9612957",
    "departure_station": "Дербент"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9612957",
    "departure_station": "Дербент"
  },
  {
    "arrival_code": "s2020500",
    "arrival_station": "Волгоград-1",
    "departure_code": "s9605700",
    "departure_station": "Донская"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607592",
    "departure_station": "Егоршино"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9609452",
    "arrival_station": "Верхний Уфалей",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607592",
    "arrival_station": "Егоршино",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9612140",
    "arrival_station": "Ижевск",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9612412",
    "arrival_station": "Красноуфимск",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9609568",
    "arrival_station": "Курган-Пасс.",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607691",
    "arrival_station": "Приобье",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607443",
    "arrival_station": "Соликамск-1",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607928",
    "arrival_station": "Сургут",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607622",
    "arrival_station": "Туринск-Уральский",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9607618",
    "arrival_station": "Устье-Аха",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9607404",
    "departure_station": "Екатеринбург-Пасс."
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611006",
    "departure_station": "Забайкальск"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s9611006",
    "departure_station": "Забайкальск"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9609518",
    "departure_station": "Златоуст"
  },
  {
    "arrival_code": "s2000001",
    "arrival_station": "Москва (Курский вокзал)",
    "departure_code": "s2010050",
    "departure_station": "Иваново"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s2010050",
    "departure_station": "Иваново"
  },
  {
    "arrival_code": "s9612336",
    "arrival_station": "Агрыз-1",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9612140",
    "departure_station": "Ижевск"
  },
  {
    "arrival_code": "s9613602",
    "arrival_station": "Краснодар-1",
    "departure_code": "s9812789",
    "departure_station": "Имеретинский курорт (Олимпийский парк)"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9812789",
    "departure_station": "Имеретинский курорт (Олимпийский парк)"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9812789",
    "departure_station": "Имеретинский курорт (Олимпийский парк)"
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s9812789",
    "departure_station": "Имеретинский курорт (Олимпийский парк)"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9812789",
    "departure_station": "Имеретинский курорт (Олимпийский парк)"
  },
  {
    "arrival_code": "s9604162",
    "arrival_station": "Печора",
    "departure_code": "s9604003",
    "departure_station": "Инта-1"
  },
  {
    "arrival_code": "s9604162",
    "arrival_station": "Печора",
    "departure_code": "s9604016",
    "departure_station": "Ираёль"
  },
  {
    "arrival_code": "s9604191",
    "arrival_station": "Сосногорск",
    "departure_code": "s9604016",
    "departure_station": "Ираёль"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611006",
    "arrival_station": "Забайкальск",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611506",
    "arrival_station": "Наушки",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9872294",
    "arrival_station": "Нижний Бестях",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611402",
    "arrival_station": "Северобайкальск",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611470",
    "arrival_station": "Тайшет",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611729",
    "arrival_station": "Улан-Удэ",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9611738",
    "arrival_station": "Усть-Илимск",
    "departure_code": "s2054001",
    "departure_station": "Иркутск-Пасс."
  },
  {
    "arrival_code": "s9610433",
    "arrival_station": "Камень-на-Оби",
    "departure_code": "s9610632",
    "departure_station": "Иртышское"
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9610597",
    "departure_station": "Исилькуль"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612396",
    "departure_station": "Йошкар-Ола"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9612396",
    "departure_station": "Йошкар-Ола"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9612620",
    "departure_station": "Казань-2 (Восстание-Пасс.)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s9606216",
    "arrival_station": "Пенза-1",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9623141",
    "departure_station": "Казань-Пасс."
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9610424",
    "departure_station": "Калачинская"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9623137",
    "departure_station": "Калининград-Южный"
  },
  {
    "arrival_code": "s9613073",
    "arrival_station": "Минеральные Воды",
    "departure_code": "s9623137",
    "departure_station": "Калининград-Южный"
  },
  {
    "arrival_code": "s2000006",
    "arrival_station": "Москва (Белорусский вокзал)",
    "departure_code": "s9623137",
    "departure_station": "Калининград-Южный"
  },
  {
    "arrival_code": "s9602496",
    "arrival_station": "Санкт-Петербург (Витебский вокзал)",
    "departure_code": "s9623137",
    "departure_station": "Калининград-Южный"
  },
  {
    "arrival_code": "s9612059",
    "arrival_station": "Светлогорск-2",
    "departure_code": "s9623137",
    "departure_station": "Калининград-Южный"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607640",
    "departure_station": "Каменск-Уральский"
  },
  {
    "arrival_code": "s9610443",
    "arrival_station": "Карасук 1",
    "departure_code": "s9610433",
    "departure_station": "Камень-на-Оби"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605814",
    "departure_station": "Камышин"
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s9608948",
    "departure_station": "Карабула"
  },
  {
    "arrival_code": "s9610632",
    "arrival_station": "Иртышское",
    "departure_code": "s9610443",
    "departure_station": "Карасук 1"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9604093",
    "departure_station": "Карпогоры-Пасс."
  },
  {
    "arrival_code": "s9601630",
    "arrival_station": "Ясногорск",
    "departure_code": "s9611156",
    "departure_station": "Карымская"
  },
  {
    "arrival_code": "s9611590",
    "arrival_station": "Зима",
    "departure_code": "s9611354",
    "departure_station": "Кая"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s2028001",
    "departure_station": "Кемерово-Пасс."
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s2028001",
    "departure_station": "Кемерово-Пасс."
  },
  {
    "arrival_code": "s9602864",
    "arrival_station": "Маленьга",
    "departure_code": "s9603212",
    "departure_station": "Кемь"
  },
  {
    "arrival_code": "s9604083",
    "arrival_station": "Котлас-Южный",
    "departure_code": "s9604061",
    "departure_station": "Кизема"
  },
  {
    "arrival_code": "s9604072",
    "arrival_station": "Кулой",
    "departure_code": "s9604061",
    "departure_station": "Кизема"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9603989",
    "departure_station": "Кинешма"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9612402",
    "departure_station": "Киров-Пасс."
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9612402",
    "departure_station": "Киров-Пасс."
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9612402",
    "departure_station": "Киров-Пасс."
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9612402",
    "departure_station": "Киров-Пасс."
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9612402",
    "departure_station": "Киров-Пасс."
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9608852",
    "arrival_station": "Заозёрная",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9612402",
    "arrival_station": "Киров-Пасс.",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9611478",
    "arrival_station": "Куанда",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9613073",
    "arrival_station": "Минеральные Воды",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9612913",
    "arrival_station": "Ростов-Главный",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9606096",
    "arrival_station": "Самара",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9611481",
    "arrival_station": "Таксимо",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9612962",
    "departure_station": "Кисловодск"
  },
  {
    "arrival_code": "s9610827",
    "arrival_station": "Могды",
    "departure_code": "s9608536",
    "departure_station": "Комсомольск-на-Амуре-Пасс."
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9608536",
    "departure_station": "Комсомольск-на-Амуре-Пасс."
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9608536",
    "departure_station": "Комсомольск-на-Амуре-Пасс."
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9603073",
    "departure_station": "Костомукша-Пасс."
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s2010090",
    "departure_station": "Кострома-Новая"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s2010090",
    "departure_station": "Кострома-Новая"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9604083",
    "departure_station": "Котлас-Южный"
  },
  {
    "arrival_code": "s9604061",
    "arrival_station": "Кизема",
    "departure_code": "s9604083",
    "departure_station": "Котлас-Южный"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9604083",
    "departure_station": "Котлас-Южный"
  },
  {
    "arrival_code": "s9882415",
    "arrival_station": "Аэропорт Сочи",
    "departure_code": "s9613602",
    "departure_station": "Краснодар-1"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9613602",
    "departure_station": "Краснодар-1"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9613602",
    "departure_station": "Краснодар-1"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9613602",
    "departure_station": "Краснодар-1"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s9611070",
    "departure_station": "Краснокаменск"
  },
  {
    "arrival_code": "s9608904",
    "arrival_station": "Абакан",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s9608948",
    "arrival_station": "Карабула",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9608687",
    "departure_station": "Красноярск-Пасс."
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606479",
    "departure_station": "Круглое Поле"
  },
  {
    "arrival_code": "s9604061",
    "arrival_station": "Кизема",
    "departure_code": "s9604072",
    "departure_station": "Кулой"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9610538",
    "departure_station": "Кулунда"
  },
  {
    "arrival_code": "s9607414",
    "arrival_station": "Верещагино",
    "departure_code": "s9607424",
    "departure_station": "Кунгур"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9609568",
    "departure_station": "Курган-Пасс."
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s9600816",
    "departure_station": "Курск"
  },
  {
    "arrival_code": "s9607928",
    "arrival_station": "Сургут",
    "departure_code": "s9607926",
    "departure_station": "Куть-Ях"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s9604103",
    "departure_station": "Лабытнанги"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9604103",
    "departure_station": "Лабытнанги"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9604103",
    "departure_station": "Лабытнанги"
  },
  {
    "arrival_code": "s9608884",
    "arrival_station": "Ачинск-1",
    "departure_code": "s9608833",
    "departure_station": "Лесосибирск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9604798",
    "departure_station": "Липецк"
  },
  {
    "arrival_code": "s9611798",
    "arrival_station": "Глубокая",
    "departure_code": "s9613486",
    "departure_station": "Лихая"
  },
  {
    "arrival_code": "s9603212",
    "arrival_station": "Кемь",
    "departure_code": "s9603200",
    "departure_station": "Лоухи"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9609478",
    "departure_station": "Магнитогорск-Пасс."
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9609478",
    "departure_station": "Магнитогорск-Пасс."
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9609495",
    "departure_station": "Макушино"
  },
  {
    "arrival_code": "s9619284",
    "arrival_station": "Семей",
    "departure_code": "s9619344",
    "departure_station": "Мангистау"
  },
  {
    "arrival_code": "s9607194",
    "arrival_station": "Тайга-1",
    "departure_code": "s9608746",
    "departure_station": "Мариинск"
  },
  {
    "arrival_code": "s9604162",
    "arrival_station": "Печора",
    "departure_code": "s9603996",
    "departure_station": "Марков"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9613649",
    "departure_station": "Махачкала"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9613649",
    "departure_station": "Махачкала"
  },
  {
    "arrival_code": "s9604009",
    "arrival_station": "Кослан",
    "departure_code": "s9604133",
    "departure_station": "Микунь"
  },
  {
    "arrival_code": "s9612967",
    "arrival_station": "Нальчик",
    "departure_code": "s9613073",
    "departure_station": "Минеральные Воды"
  },
  {
    "arrival_code": "s9613998",
    "arrival_station": "Барановичи-Полесские",
    "departure_code": "s9613990",
    "departure_station": "Минск (Институт Культуры)"
  },
  {
    "arrival_code": "s9614111",
    "arrival_station": "Столбцы",
    "departure_code": "s9613990",
    "departure_station": "Минск (Институт Культуры)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9613998",
    "arrival_station": "Барановичи-Полесские",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9614023",
    "arrival_station": "Брест-Центр.",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9614088",
    "arrival_station": "Гомель-Пасс.",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9614058",
    "arrival_station": "Гродно",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s2000006",
    "arrival_station": "Москва (Белорусский вокзал)",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9602693",
    "arrival_station": "Мурманск",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9602496",
    "arrival_station": "Санкт-Петербург (Витебский вокзал)",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9614111",
    "arrival_station": "Столбцы",
    "departure_code": "s9613989",
    "departure_station": "Минск-Пасс."
  },
  {
    "arrival_code": "s9614023",
    "arrival_station": "Брест-Центр.",
    "departure_code": "s2000006",
    "departure_station": "Москва (Белорусский вокзал)"
  },
  {
    "arrival_code": "s9623137",
    "arrival_station": "Калининград-Южный",
    "departure_code": "s2000006",
    "departure_station": "Москва (Белорусский вокзал)"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s2000006",
    "departure_station": "Москва (Белорусский вокзал)"
  },
  {
    "arrival_code": "s9602993",
    "arrival_station": "Псков-Пасс.",
    "departure_code": "s2000006",
    "departure_station": "Москва (Белорусский вокзал)"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s2000006",
    "departure_station": "Москва (Белорусский вокзал)"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9879173",
    "departure_station": "Москва (Восточный вокзал)"
  },
  {
    "arrival_code": "s2014360",
    "arrival_station": "Валуйки",
    "departure_code": "s9879173",
    "departure_station": "Москва (Восточный вокзал)"
  },
  {
    "arrival_code": "s9600816",
    "arrival_station": "Курск",
    "departure_code": "s9879173",
    "departure_station": "Москва (Восточный вокзал)"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9879173",
    "departure_station": "Москва (Восточный вокзал)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606444",
    "arrival_station": "Аксаково",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607092",
    "arrival_station": "Белово",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610404",
    "arrival_station": "Бийск",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s2014001",
    "arrival_station": "Воронеж-1",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613083",
    "arrival_station": "Грозный",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612140",
    "arrival_station": "Ижевск",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612396",
    "arrival_station": "Йошкар-Ола",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s2028001",
    "arrival_station": "Кемерово-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606479",
    "arrival_station": "Круглое Поле",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610850",
    "arrival_station": "Кувыкта",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9604798",
    "arrival_station": "Липецк",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612961",
    "arrival_station": "Назрань",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9609448",
    "arrival_station": "Орск",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606216",
    "arrival_station": "Пенза-1",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612211",
    "arrival_station": "Первомайск-Горьковский",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607691",
    "arrival_station": "Приобье",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612913",
    "arrival_station": "Ростов-Главный",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9600786",
    "arrival_station": "Рязань-1",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606096",
    "arrival_station": "Самара",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606464",
    "arrival_station": "Саранск-1",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9617002",
    "arrival_station": "Севастополь",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613524",
    "arrival_station": "Ставрополь",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9607928",
    "arrival_station": "Сургут",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9620203",
    "arrival_station": "Сухум",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613171",
    "arrival_station": "Таганрог-1-пасс.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606570",
    "arrival_station": "Тольятти",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9611729",
    "arrival_station": "Улан-Удэ",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606620",
    "arrival_station": "Ульяновск-Центр.",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9606364",
    "arrival_station": "Уфа",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9612421",
    "arrival_station": "Чебоксары",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s2000003",
    "departure_station": "Москва (Казанский вокзал)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s2000007",
    "departure_station": "Москва (Киевский вокзал)"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s2000007",
    "departure_station": "Москва (Киевский вокзал)"
  },
  {
    "arrival_code": "s9600826",
    "arrival_station": "Брянск-1-Орловский",
    "departure_code": "s2000007",
    "departure_station": "Москва (Киевский вокзал)"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s2000007",
    "departure_station": "Москва (Киевский вокзал)"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s2000001",
    "departure_station": "Москва (Курский вокзал)"
  },
  {
    "arrival_code": "s9602893",
    "arrival_station": "Великий Новгород",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9872408",
    "arrival_station": "Горный парк Рускеала",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9602693",
    "arrival_station": "Мурманск",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9602793",
    "arrival_station": "Петрозаводск-Пасс.",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9602993",
    "arrival_station": "Псков-Пасс.",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s2006004",
    "departure_station": "Москва (Ленинградский вокзал)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605765",
    "arrival_station": "Астрахань-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605909",
    "arrival_station": "Балаково",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9612982",
    "arrival_station": "Владикавказ",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s2020500",
    "arrival_station": "Волгоград-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s2014001",
    "arrival_station": "Воронеж-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9612957",
    "arrival_station": "Дербент",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605814",
    "arrival_station": "Камышин",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9613602",
    "arrival_station": "Краснодар-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9612967",
    "arrival_station": "Нальчик",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9609448",
    "arrival_station": "Орск",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9606216",
    "arrival_station": "Пенза-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605143",
    "arrival_station": "Придача (Воронеж-Южный)",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9623135",
    "arrival_station": "Саратов-1-Пасс.",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9605291",
    "arrival_station": "Тамбов-1",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9620410",
    "arrival_station": "Ташкент-Центральный",
    "departure_code": "s2000005",
    "departure_station": "Москва (Павелецкий вокзал)"
  },
  {
    "arrival_code": "s9608904",
    "arrival_station": "Абакан",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9603962",
    "arrival_station": "Вологда-1",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607540",
    "arrival_station": "Гороблагодатская",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9603989",
    "arrival_station": "Кинешма",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9612402",
    "arrival_station": "Киров-Пасс.",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s2010090",
    "arrival_station": "Кострома-Новая",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9604103",
    "arrival_station": "Лабытнанги",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607483",
    "arrival_station": "Нижний Тагил",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607691",
    "arrival_station": "Приобье",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9611402",
    "arrival_station": "Северобайкальск",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607543",
    "arrival_station": "Серов",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607194",
    "arrival_station": "Тайга-1",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9604250",
    "arrival_station": "Усинск",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9604211",
    "arrival_station": "Череповец-1",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9603934",
    "arrival_station": "Ярославль-Главный",
    "departure_code": "s2000002",
    "departure_station": "Москва (Ярославский вокзал)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9603033",
    "arrival_station": "Великие Луки",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9603063",
    "arrival_station": "Дно",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9603212",
    "arrival_station": "Кемь",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s2006004",
    "arrival_station": "Москва (Ленинградский вокзал)",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9602993",
    "arrival_station": "Псков-Пасс.",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s9602693",
    "departure_station": "Мурманск"
  },
  {
    "arrival_code": "s9613073",
    "arrival_station": "Минеральные Воды",
    "departure_code": "s9612967",
    "departure_station": "Нальчик"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9612967",
    "departure_station": "Нальчик"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611506",
    "departure_station": "Наушки"
  },
  {
    "arrival_code": "s2010090",
    "arrival_station": "Кострома-Новая",
    "departure_code": "s9604137",
    "departure_station": "Нерехта"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9610855",
    "departure_station": "Нерюнгри-Пасс."
  },
  {
    "arrival_code": "s9613073",
    "arrival_station": "Минеральные Воды",
    "departure_code": "s9610855",
    "departure_station": "Нерюнгри-Пасс."
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9610855",
    "departure_station": "Нерюнгри-Пасс."
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9610855",
    "departure_station": "Нерюнгри-Пасс."
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9610855",
    "departure_station": "Нерюнгри-Пасс."
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9605765",
    "arrival_station": "Астрахань-1",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s2020500",
    "arrival_station": "Волгоград-1",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9606216",
    "arrival_station": "Пенза-1",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9607928",
    "arrival_station": "Сургут",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9607699",
    "departure_station": "Нижневартовск-1"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9872294",
    "departure_station": "Нижний Бестях"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9872294",
    "departure_station": "Нижний Бестях"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9872294",
    "departure_station": "Нижний Бестях"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9872294",
    "departure_station": "Нижний Бестях"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9612140",
    "arrival_station": "Ижевск",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9604083",
    "arrival_station": "Котлас-Южный",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9604103",
    "arrival_station": "Лабытнанги",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9879173",
    "arrival_station": "Москва (Восточный вокзал)",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9612199",
    "arrival_station": "Мухтолово",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9604250",
    "arrival_station": "Усинск",
    "departure_code": "s9612089",
    "departure_station": "Нижний Новгород (Московский вокзал)"
  },
  {
    "arrival_code": "s9607691",
    "arrival_station": "Приобье",
    "departure_code": "s9607483",
    "departure_station": "Нижний Тагил"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9600199",
    "departure_station": "Николо-Полома"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9611452",
    "departure_station": "Новая Чара"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9607192",
    "departure_station": "Новокузнецк"
  },
  {
    "arrival_code": "s9612982",
    "arrival_station": "Владикавказ",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9600746",
    "arrival_station": "Рязань-2",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9613483",
    "arrival_station": "Таганрог (старый вокзал)",
    "departure_code": "s9613022",
    "departure_station": "Новороссийск"
  },
  {
    "arrival_code": "s9608904",
    "arrival_station": "Абакан",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9618977",
    "arrival_station": "Алматы-2",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610374",
    "arrival_station": "Барабинск",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9603479",
    "arrival_station": "Болотная",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610538",
    "arrival_station": "Кулунда",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9619237",
    "arrival_station": "Риддер",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s9610189",
    "departure_station": "Новосибирск-главный"
  },
  {
    "arrival_code": "s9612140",
    "arrival_station": "Ижевск",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9612254",
    "arrival_station": "Котельнич-1",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9609498",
    "arrival_station": "Миасс-1",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s9606364",
    "arrival_station": "Уфа",
    "departure_code": "s9607710",
    "departure_station": "Новый Уренгой"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9604152",
    "departure_station": "Обозерская"
  },
  {
    "arrival_code": "s9612336",
    "arrival_station": "Агрыз-1",
    "departure_code": "s9611450",
    "departure_station": "Окусикан"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9611450",
    "departure_station": "Окусикан"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9610860",
    "departure_station": "Олёкма"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9610384",
    "departure_station": "Омск-Пасс."
  },
  {
    "arrival_code": "s9610597",
    "arrival_station": "Исилькуль",
    "departure_code": "s9610384",
    "departure_station": "Омск-Пасс."
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9610384",
    "departure_station": "Омск-Пасс."
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9610384",
    "departure_station": "Омск-Пасс."
  },
  {
    "arrival_code": "s9610493",
    "arrival_station": "Рубцовск",
    "departure_code": "s9610384",
    "departure_station": "Омск-Пасс."
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9607706",
    "arrival_station": "Пуровск",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9606096",
    "arrival_station": "Самара",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9607618",
    "arrival_station": "Устье-Аха",
    "departure_code": "s9609240",
    "departure_station": "Оренбург"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9609448",
    "departure_station": "Орск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9609448",
    "departure_station": "Орск"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9609448",
    "departure_station": "Орск"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9609448",
    "departure_station": "Орск"
  },
  {
    "arrival_code": "s9606374",
    "arrival_station": "Сызрань-1",
    "departure_code": "s9609448",
    "departure_station": "Орск"
  },
  {
    "arrival_code": "s9600826",
    "arrival_station": "Брянск-1-Орловский",
    "departure_code": "s9600806",
    "departure_station": "Орёл"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9606216",
    "departure_station": "Пенза-1"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s9606216",
    "departure_station": "Пенза-1"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9606216",
    "departure_station": "Пенза-1"
  },
  {
    "arrival_code": "s9607699",
    "arrival_station": "Нижневартовск-1",
    "departure_code": "s9606216",
    "departure_station": "Пенза-1"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612211",
    "departure_station": "Первомайск-Горьковский"
  },
  {
    "arrival_code": "s9607572",
    "arrival_station": "Голованово",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9612620",
    "arrival_station": "Казань-2 (Восстание-Пасс.)",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9607417",
    "arrival_station": "Лёвшино",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9613089",
    "arrival_station": "Северская",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9607426",
    "arrival_station": "Углеуральская",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9613104",
    "arrival_station": "Шахтная",
    "departure_code": "s9607774",
    "departure_station": "Пермь-2"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9602793",
    "departure_station": "Петрозаводск-Пасс."
  },
  {
    "arrival_code": "s9604003",
    "arrival_station": "Инта-1",
    "departure_code": "s9604162",
    "departure_station": "Печора"
  },
  {
    "arrival_code": "s9604016",
    "arrival_station": "Ираёль",
    "departure_code": "s9604162",
    "departure_station": "Печора"
  },
  {
    "arrival_code": "s9603996",
    "arrival_station": "Марков",
    "departure_code": "s9604162",
    "departure_station": "Печора"
  },
  {
    "arrival_code": "s2060340",
    "arrival_station": "Владимир",
    "departure_code": "s2001005",
    "departure_station": "Площадь трёх вокзалов (Каланчёвская)"
  },
  {
    "arrival_code": "s9610944",
    "arrival_station": "Чита-2",
    "departure_code": "s9610988",
    "departure_station": "Приаргунск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607691",
    "departure_station": "Приобье"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9607691",
    "departure_station": "Приобье"
  },
  {
    "arrival_code": "s2006004",
    "arrival_station": "Москва (Ленинградский вокзал)",
    "departure_code": "s9602993",
    "departure_station": "Псков-Пасс."
  },
  {
    "arrival_code": "s9602693",
    "arrival_station": "Мурманск",
    "departure_code": "s9602993",
    "departure_station": "Псков-Пасс."
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610519",
    "departure_station": "Ребриха"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9619237",
    "departure_station": "Риддер"
  },
  {
    "arrival_code": "s9613602",
    "arrival_station": "Краснодар-1",
    "departure_code": "s9812791",
    "departure_station": "Роза Хутор"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s9613045",
    "arrival_station": "Азов",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s9882415",
    "arrival_station": "Аэропорт Сочи",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s9613171",
    "arrival_station": "Таганрог-1-пасс.",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s9607193",
    "arrival_station": "Томск-2",
    "departure_code": "s9612913",
    "departure_station": "Ростов-Главный"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605308",
    "departure_station": "Ртищево-1"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610493",
    "departure_station": "Рубцовск"
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9610493",
    "departure_station": "Рубцовск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9600786",
    "departure_station": "Рязань-1"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s9623135",
    "arrival_station": "Саратов-1-Пасс.",
    "departure_code": "s9606096",
    "departure_station": "Самара"
  },
  {
    "arrival_code": "s9602993",
    "arrival_station": "Псков-Пасс.",
    "departure_code": "s9602498",
    "departure_station": "Санкт-Петербург (Балтийский вокзал)"
  },
  {
    "arrival_code": "s9614023",
    "arrival_station": "Брест-Центр.",
    "departure_code": "s9602496",
    "departure_station": "Санкт-Петербург (Витебский вокзал)"
  },
  {
    "arrival_code": "s9603033",
    "arrival_station": "Великие Луки",
    "departure_code": "s9602496",
    "departure_station": "Санкт-Петербург (Витебский вокзал)"
  },
  {
    "arrival_code": "s9623137",
    "arrival_station": "Калининград-Южный",
    "departure_code": "s9602496",
    "departure_station": "Санкт-Петербург (Витебский вокзал)"
  },
  {
    "arrival_code": "s9613989",
    "arrival_station": "Минск-Пасс.",
    "departure_code": "s9602496",
    "departure_station": "Санкт-Петербург (Витебский вокзал)"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s9602496",
    "departure_station": "Санкт-Петербург (Витебский вокзал)"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9603962",
    "arrival_station": "Вологда-1",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9612140",
    "arrival_station": "Ижевск",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9612402",
    "arrival_station": "Киров-Пасс.",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9603073",
    "arrival_station": "Костомукша-Пасс.",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9604083",
    "arrival_station": "Котлас-Южный",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9602793",
    "arrival_station": "Петрозаводск-Пасс.",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9603964",
    "arrival_station": "Плесецкая",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9604201",
    "arrival_station": "Сыктывкар",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9602499",
    "departure_station": "Санкт-Петербург (Ладожский вокзал)"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9605765",
    "arrival_station": "Астрахань-1",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9605027",
    "arrival_station": "Белгород",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9600826",
    "arrival_station": "Брянск-1-Орловский",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9612982",
    "arrival_station": "Владикавказ",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2020500",
    "arrival_station": "Волгоград-1",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2010050",
    "arrival_station": "Иваново",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9612396",
    "arrival_station": "Йошкар-Ола",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9623141",
    "arrival_station": "Казань-Пасс.",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2010090",
    "arrival_station": "Кострома-Новая",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9613649",
    "arrival_station": "Махачкала",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2000007",
    "arrival_station": "Москва (Киевский вокзал)",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2006004",
    "arrival_station": "Москва (Ленинградский вокзал)",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9606096",
    "arrival_station": "Самара",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9617002",
    "arrival_station": "Севастополь",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9600836",
    "arrival_station": "Смоленск",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9603163",
    "arrival_station": "Старая Русса",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2014340",
    "arrival_station": "Старый Оскол",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9606364",
    "arrival_station": "Уфа",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s9603694",
    "arrival_station": "Шапки",
    "departure_code": "s9602494",
    "departure_station": "Санкт-Петербург (Московский вокзал)"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9610514",
    "departure_station": "Сарайский"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606464",
    "departure_station": "Саранск-1"
  },
  {
    "arrival_code": "s9618977",
    "arrival_station": "Алматы-2",
    "departure_code": "s9623135",
    "departure_station": "Саратов-1-Пасс."
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9623135",
    "departure_station": "Саратов-1-Пасс."
  },
  {
    "arrival_code": "s9600759",
    "arrival_station": "Узуново",
    "departure_code": "s9623135",
    "departure_station": "Саратов-1-Пасс."
  },
  {
    "arrival_code": "s9623137",
    "arrival_station": "Калининград-Южный",
    "departure_code": "s9612059",
    "departure_station": "Светлогорск-2"
  },
  {
    "arrival_code": "s9612294",
    "arrival_station": "Яр",
    "departure_code": "s9612753",
    "departure_station": "Светлополянск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9617002",
    "departure_station": "Севастополь"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9617002",
    "departure_station": "Севастополь"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9611402",
    "departure_station": "Северобайкальск"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611402",
    "departure_station": "Северобайкальск"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9611402",
    "departure_station": "Северобайкальск"
  },
  {
    "arrival_code": "s9611729",
    "arrival_station": "Улан-Удэ",
    "departure_code": "s9611402",
    "departure_station": "Северобайкальск"
  },
  {
    "arrival_code": "s9612294",
    "arrival_station": "Яр",
    "departure_code": "s9611402",
    "departure_station": "Северобайкальск"
  },
  {
    "arrival_code": "s9603962",
    "arrival_station": "Вологда-1",
    "departure_code": "s2010320",
    "departure_station": "Северодвинск"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s2010320",
    "departure_station": "Северодвинск"
  },
  {
    "arrival_code": "s9619344",
    "arrival_station": "Мангистау",
    "departure_code": "s9619284",
    "departure_station": "Семей"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9607543",
    "departure_station": "Серов"
  },
  {
    "arrival_code": "s9606364",
    "arrival_station": "Уфа",
    "departure_code": "s9609369",
    "departure_station": "Сибай"
  },
  {
    "arrival_code": "s9612336",
    "arrival_station": "Агрыз-1",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9610384",
    "arrival_station": "Омск-Пасс.",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9607774",
    "arrival_station": "Пермь-2",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9616627",
    "departure_station": "Симферополь-Пасс."
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610419",
    "departure_station": "Славгород"
  },
  {
    "arrival_code": "s9613998",
    "arrival_station": "Барановичи-Полесские",
    "departure_code": "s9614043",
    "departure_station": "Слуцк"
  },
  {
    "arrival_code": "s9614051",
    "arrival_station": "Солигорск",
    "departure_code": "s9614043",
    "departure_station": "Слуцк"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s2010400",
    "arrival_station": "Архангельск-Город",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s2000006",
    "arrival_station": "Москва (Белорусский вокзал)",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s9602693",
    "arrival_station": "Мурманск",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s9602496",
    "arrival_station": "Санкт-Петербург (Витебский вокзал)",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9600836",
    "departure_station": "Смоленск"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9608543",
    "departure_station": "Советская Гавань-Сорт."
  },
  {
    "arrival_code": "s9614043",
    "arrival_station": "Слуцк",
    "departure_code": "s9614051",
    "departure_station": "Солигорск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607443",
    "departure_station": "Соликамск-1"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9603047",
    "departure_station": "Сонково"
  },
  {
    "arrival_code": "s9604016",
    "arrival_station": "Ираёль",
    "departure_code": "s9604191",
    "departure_station": "Сосногорск"
  },
  {
    "arrival_code": "s9607563",
    "arrival_station": "Алапаевск",
    "departure_code": "s9607594",
    "departure_station": "Сосьва-новая"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9608574",
    "departure_station": "Спасск-Дальний"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9613524",
    "departure_station": "Ставрополь"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613524",
    "departure_station": "Ставрополь"
  },
  {
    "arrival_code": "s9600816",
    "arrival_station": "Курск",
    "departure_code": "s2014340",
    "departure_station": "Старый Оскол"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s2014340",
    "departure_station": "Старый Оскол"
  },
  {
    "arrival_code": "s9613990",
    "arrival_station": "Минск (Институт Культуры)",
    "departure_code": "s9614111",
    "departure_station": "Столбцы"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607928",
    "departure_station": "Сургут"
  },
  {
    "arrival_code": "s9607926",
    "arrival_station": "Куть-Ях",
    "departure_code": "s9607928",
    "departure_station": "Сургут"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9607928",
    "departure_station": "Сургут"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9620203",
    "departure_station": "Сухум"
  },
  {
    "arrival_code": "s9606246",
    "arrival_station": "Мирная",
    "departure_code": "s9606374",
    "departure_station": "Сызрань-1"
  },
  {
    "arrival_code": "s9603971",
    "arrival_station": "Воркута",
    "departure_code": "s9604201",
    "departure_station": "Сыктывкар"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9604201",
    "departure_station": "Сыктывкар"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9604201",
    "departure_station": "Сыктывкар"
  },
  {
    "arrival_code": "s9604250",
    "arrival_station": "Усинск",
    "departure_code": "s9604201",
    "departure_station": "Сыктывкар"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9613483",
    "departure_station": "Таганрог (старый вокзал)"
  },
  {
    "arrival_code": "s9613022",
    "arrival_station": "Новороссийск",
    "departure_code": "s9613483",
    "departure_station": "Таганрог (старый вокзал)"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9613171",
    "departure_station": "Таганрог-1-пасс."
  },
  {
    "arrival_code": "s9612913",
    "arrival_station": "Ростов-Главный",
    "departure_code": "s9613171",
    "departure_station": "Таганрог-1-пасс."
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9613171",
    "departure_station": "Таганрог-1-пасс."
  },
  {
    "arrival_code": "s9607192",
    "arrival_station": "Новокузнецк",
    "departure_code": "s9607194",
    "departure_station": "Тайга-1"
  },
  {
    "arrival_code": "s9607190",
    "arrival_station": "Томск-1",
    "departure_code": "s9607194",
    "departure_station": "Тайга-1"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9607194",
    "departure_station": "Тайга-1"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611470",
    "departure_station": "Тайшет"
  },
  {
    "arrival_code": "s9611738",
    "arrival_station": "Усть-Илимск",
    "departure_code": "s9611470",
    "departure_station": "Тайшет"
  },
  {
    "arrival_code": "s2000005",
    "arrival_station": "Москва (Павелецкий вокзал)",
    "departure_code": "s9605291",
    "departure_station": "Тамбов-1"
  },
  {
    "arrival_code": "s9610483",
    "arrival_station": "Барнаул",
    "departure_code": "s9610503",
    "departure_station": "Татарская"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9620410",
    "departure_station": "Ташкент-Центральный"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9608650",
    "departure_station": "Тихоокеанская"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9607680",
    "departure_station": "Тобольск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606570",
    "departure_station": "Тольятти"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9613091",
    "arrival_station": "Анапа",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9607338",
    "arrival_station": "Белый Яр",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9610404",
    "arrival_station": "Бийск",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9607193",
    "departure_station": "Томск-2"
  },
  {
    "arrival_code": "s9611603",
    "arrival_station": "Нижнеудинск",
    "departure_code": "s9611634",
    "departure_station": "Тулун"
  },
  {
    "arrival_code": "s9611586",
    "arrival_station": "Ангарск",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9608536",
    "arrival_station": "Комсомольск-на-Амуре-Пасс.",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9608687",
    "arrival_station": "Красноярск-Пасс.",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9872294",
    "arrival_station": "Нижний Бестях",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9611729",
    "arrival_station": "Улан-Удэ",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9609235",
    "arrival_station": "Челябинск-Главный",
    "departure_code": "s9610775",
    "departure_station": "Тында"
  },
  {
    "arrival_code": "s9612957",
    "arrival_station": "Дербент",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9612962",
    "arrival_station": "Кисловодск",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9613649",
    "arrival_station": "Махачкала",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9602499",
    "arrival_station": "Санкт-Петербург (Ладожский вокзал)",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9616627",
    "arrival_station": "Симферополь-Пасс.",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9607680",
    "arrival_station": "Тобольск",
    "departure_code": "s9607503",
    "departure_station": "Тюмень"
  },
  {
    "arrival_code": "s9610374",
    "arrival_station": "Барабинск",
    "departure_code": "s9611729",
    "departure_station": "Улан-Удэ"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611729",
    "departure_station": "Улан-Удэ"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9611729",
    "departure_station": "Улан-Удэ"
  },
  {
    "arrival_code": "s9612186",
    "arrival_station": "Навашино",
    "departure_code": "s9611729",
    "departure_station": "Улан-Удэ"
  },
  {
    "arrival_code": "s9611402",
    "arrival_station": "Северобайкальск",
    "departure_code": "s9611729",
    "departure_station": "Улан-Удэ"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606620",
    "departure_station": "Ульяновск-Центр."
  },
  {
    "arrival_code": "s9619344",
    "arrival_station": "Мангистау",
    "departure_code": "s9619347",
    "departure_station": "Уральск"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s9612089",
    "arrival_station": "Нижний Новгород (Московский вокзал)",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s9604162",
    "arrival_station": "Печора",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s9604201",
    "arrival_station": "Сыктывкар",
    "departure_code": "s9604250",
    "departure_station": "Усинск"
  },
  {
    "arrival_code": "s2054001",
    "arrival_station": "Иркутск-Пасс.",
    "departure_code": "s9611738",
    "departure_station": "Усть-Илимск"
  },
  {
    "arrival_code": "s9610189",
    "arrival_station": "Новосибирск-главный",
    "departure_code": "s9611738",
    "departure_station": "Усть-Илимск"
  },
  {
    "arrival_code": "s9611470",
    "arrival_station": "Тайшет",
    "departure_code": "s9611738",
    "departure_station": "Усть-Илимск"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9607618",
    "departure_station": "Устье-Аха"
  },
  {
    "arrival_code": "s9609240",
    "arrival_station": "Оренбург",
    "departure_code": "s9607618",
    "departure_station": "Устье-Аха"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9606364",
    "departure_station": "Уфа"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9606364",
    "departure_station": "Уфа"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9606364",
    "departure_station": "Уфа"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9606364",
    "departure_station": "Уфа"
  },
  {
    "arrival_code": "s9609369",
    "arrival_station": "Сибай",
    "departure_code": "s9606364",
    "departure_station": "Уфа"
  },
  {
    "arrival_code": "s9603934",
    "arrival_station": "Ярославль-Главный",
    "departure_code": "s9603937",
    "departure_station": "Филино"
  },
  {
    "arrival_code": "s9608407",
    "arrival_station": "Биробиджан-1",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9608404",
    "arrival_station": "Владивосток",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9608517",
    "arrival_station": "Вяземская",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9608536",
    "arrival_station": "Комсомольск-на-Амуре-Пасс.",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9610855",
    "arrival_station": "Нерюнгри-Пасс.",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9608650",
    "arrival_station": "Тихоокеанская",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9610775",
    "arrival_station": "Тында",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9610903",
    "arrival_station": "Чегдомын",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9611126",
    "arrival_station": "Шимановская",
    "departure_code": "s9608401",
    "departure_station": "Хабаровск-1"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9608530",
    "departure_station": "Хор"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9612421",
    "departure_station": "Чебоксары"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9612421",
    "departure_station": "Чебоксары"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9610903",
    "departure_station": "Чегдомын"
  },
  {
    "arrival_code": "s9607404",
    "arrival_station": "Екатеринбург-Пасс.",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s9812789",
    "arrival_station": "Имеретинский курорт (Олимпийский парк)",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s9609478",
    "arrival_station": "Магнитогорск-Пасс.",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s2000003",
    "arrival_station": "Москва (Казанский вокзал)",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s9607710",
    "arrival_station": "Новый Уренгой",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s9602494",
    "arrival_station": "Санкт-Петербург (Московский вокзал)",
    "departure_code": "s9609235",
    "departure_station": "Челябинск-Главный"
  },
  {
    "arrival_code": "s9603962",
    "arrival_station": "Вологда-1",
    "departure_code": "s9604211",
    "departure_station": "Череповец-1"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9604211",
    "departure_station": "Череповец-1"
  },
  {
    "arrival_code": "s9613054",
    "arrival_station": "Адлер",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9611007",
    "arrival_station": "Благовещенск",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9611006",
    "arrival_station": "Забайкальск",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9611070",
    "arrival_station": "Краснокаменск",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9604179",
    "arrival_station": "Нея",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9610988",
    "arrival_station": "Приаргунск",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9607503",
    "arrival_station": "Тюмень",
    "departure_code": "s9610944",
    "departure_station": "Чита-2"
  },
  {
    "arrival_code": "s9608401",
    "arrival_station": "Хабаровск-1",
    "departure_code": "s9611126",
    "departure_station": "Шимановская"
  },
  {
    "arrival_code": "s9601547",
    "arrival_station": "Александров-1",
    "departure_code": "s9603934",
    "departure_station": "Ярославль-Главный"
  },
  {
    "arrival_code": "s2000002",
    "arrival_station": "Москва (Ярославский вокзал)",
    "departure_code": "s9603934",
    "departure_station": "Ярославль-Главный"
  }
]';

//clearDateBase($link,$train);

function clearDateBase($link, $array){

$i = 0;
$j = 1;
$obj = json_decode($array, true);
foreach ($obj as $value){
    $arr_st = $value['arrival_station'];
	$dep_st = $value['departure_station'];
	$arr_code = $value['arrival_code'];
	$dep_code = $value['departure_code'];
	$query = "INSERT INTO `aw_train_list`(`id`, `dep_station`, `arr_station`, `dep_code`, `arr_code`, `queue`, `status`) VALUES 
	(NULL,'".$dep_st."','".$arr_st."','".$dep_code."','".$arr_code."','".$j."',0)";
	$result = mysqli_query($link, $query);
	if($i>99){
		$i = 0;
		$j++;
    }
	$i++;
	
}

}



?>