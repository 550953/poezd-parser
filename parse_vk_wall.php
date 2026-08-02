
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include 'simple_html_dom.php';
function get_web_page( $url )
{
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // Ð²Ð¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð²ÐµÐ±-ÑÑÑÐ°Ð½Ð¸ÑÑ
    curl_setopt($ch, CURLOPT_HEADER, 0);           // Ð½Ðµ Ð²Ð¾Ð·Ð²ÑÐ°ÑÐ°ÐµÑ Ð·Ð°Ð³Ð¾Ð»Ð¾Ð²ÐºÐ¸
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // Ð¿ÐµÑÐµÑÐ¾Ð´Ð¸Ñ Ð¿Ð¾ ÑÐµÐ´Ð¸ÑÐµÐºÑÐ°Ð¼
    curl_setopt($ch, CURLOPT_ENCODING, "");        // Ð¾Ð±ÑÐ°Ð±Ð°ÑÑÐ²Ð°ÐµÑ Ð²ÑÐµ ÐºÐ¾Ð´Ð¸ÑÐ¾Ð²ÐºÐ¸
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // ÑÐ°Ð¹Ð¼Ð°ÑÑ ÑÐ¾ÐµÐ´Ð¸Ð½ÐµÐ½Ð¸Ñ
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // ÑÐ°Ð¹Ð¼Ð°ÑÑ Ð¾ÑÐ²ÐµÑÐ°
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // Ð¾ÑÑÐ°Ð½Ð°Ð²Ð»Ð¸Ð²Ð°ÑÑÑÑ Ð¿Ð¾ÑÐ»Ðµ 10-Ð¾Ð³Ð¾ ÑÐµÐ´Ð¸ÑÐµÐºÑÐ°

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
$rss ="https://vkrss.com/provodnikfpk" ;

$xmlstr = @file_get_contents($rss);




if($xmlstr===false)die('Error connect to RSS: '.$xmlstr);
//
//$json = json_encode($xmlstr);
//$array = json_decode($json,TRUE);
//
//print_r($json);


$xml = new SimpleXMLElement($xmlstr);

echo $xml;
if($xml===false)die('Error parse RSS: '.$xmlstr);

//foreach ($xml as $l)
//    echo htmlentities($l->asXML());
//echo $xml->channel -> item[0]->asXML();

foreach($xml->xpath('//item') as $item) {
    echo '' . $item->title . '(' . $item->description . ')';
}

//$result = get_web_page($url);
//$err = 0;
//if ($result['errno'] != 0) { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÐ¿ÑÐ°Ð²Ð¸Ð»ÑÐ½ÑÐ¹ url, ÑÐ°Ð¹Ð¼Ð°ÑÑ, Ð·Ð°ÑÐ¸ÐºÐ»Ð¸Ð²Ð°Ð½Ð¸Ðµ ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
//    $result = get_web_page($url);
//    $err = 1;
//}
//
//if ($result['http_code'] != 200) { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÑ ÑÑÑÐ°Ð½Ð¸ÑÑ, Ð½ÐµÑ Ð¿ÑÐ°Ð² ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
//    $err = 1;
//}
//
//$page = $result['content'];
//
//$html = str_get_html($page);
//echo $html;
//if ($html->find('#vk_groups .widget_body')) {
//    echo "find";
//    // echo count($html->find('table[id=train_list_table] tr'));
//    foreach ($html->find('div[id="page_wall_posts"]') as $divs) {
//          echo count($divs -> find('div[class="wcommunity_post"]'));
//
//    }
//}

