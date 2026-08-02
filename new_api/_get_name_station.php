<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-type: application/json');

include '../simple_html_dom.php';

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


function getName($url){

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
    if(!isset($html)){
       return;
    }

    $h1 = $html->find('h1[class="title__mainTitle__11h-C o3310 o3328 o3313"]',0)->innertext;
	$pieces = explode(": ", $h1);
        $result = $pieces[count($pieces) - 1];
		return $result;
}




$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$station = $data['station'];


$station = $_GET['station'];
// $end = $_GET['end'];
// $date = $_GET['date'];
// $train = $_GET['train'];

if($station != null){
	$url = "https://www.tutu.ru/poezda/station_d.php?nnst=".$station;
   
	$result = getName($url);
    $res = array('name' => $result);

    echo json_encode($res);
	

}
?>