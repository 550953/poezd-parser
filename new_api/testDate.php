<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
include '../simple_html_dom.php';
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
$train = $_GET['train'];
$date = $_GET['date'];

if($train != null && $date != null){
    echo "<PRE>";
	print_r(ParseTrainInfo($train, $date));
	echo "</PRE>"; 
	//echo ParseTrainInfo($trains, $dateStart);
}else{
 	echo "ERROR";
}



function ParseTrainInfo($train, $date)
{
    $trains = urlencode($train);
    
    if($trains != null){
        $url = "https://rasp.yandex.ru/thread/378S_0_2?departure_from=2019-11-06+17%3A35%3A00&station_from=9613022&station_to=2000005";
    }
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

        $table = $html->find('div[class="ThreadTable ThreadPage__table"]');
		$i = 0;
        foreach($table as $row){
        	$result_row = $row -> find('div[class="ThreadTable__wrapperInner"]',0);
            echo "COUNT = ".$i;
        	$i++;
            
        }
echo "ERROR = ".$err;



return $result;
}

mysqli_close($link);
?>