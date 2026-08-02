<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
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


function getTrainLoss($url, $train){

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
	$table = $html->find('tr[class="trlist__trlist-row"]');
	foreach($table as $row){
    	$link = $row -> find('a[class="greyLink"]',0)-> href;
    	$link = str_replace('amp;', '', $link);
        //$train = urlencode($train);
     //   echo $link;
 //  echo urldecode($link)." ".$train."<br>";
        $pos = strpos(urldecode($link), $train);
    	if($pos === false){
       	 
    	}else{
        $urls[] = "http://pass.rzd.ru".$link;
    	}
	}
  /////Добавить выдор строки по параметрам
  //
  //
	
$queryUrl = $urls[0];
//echo $queryUrl;

if(isset($queryUrl)){
	$result = get_web_page($queryUrl);
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
	$table = $html->find('table[class="table full-width"]',0) -> find('tr');
    $record = false;
    $prib[] = "";
    $prib_f[] = "";
	foreach($table as $row){
    	//$link = $row -> find('div[class="datetime-msk"]') ;
   	//	print_r($row->children[0]);
    if(isset($row)){
    	$b = $row-> class;
        if($b != "techflag1 tablo_strow_transit" && $b != "tablo_strow_notRoute"){
        
        
        
          $el1 = $row-> find('td[class="cT"]',0);
   
        if(isset($el1)){
        	$el1 = $el1->find('div[class="datetime-msk"]',0);
        	if(isset($el1)){
       			$time = $el1 -> find('b[class="trlist__cell-pointdata__time"]',0)-> innertext;
                $data = $el1 -> find('span[class="trlist__cell-pointdata__date-sub"]',0)-> innertext;
                $trim = full_trim_new($time)." ".full_trim_new($data);
            //	$trim = full_trim($find);
            	if($trim == ""){
                	$prib[] = "";
                }else{
                	$prib[] = $trim;
                }
        	}
        }
        
        $el_abs1 = $row-> find('td[class="absrel-abs cT"]',0);
   
        if(isset($el_abs1)){
        	$el_abs1 = $el_abs1->find('div[class="datetime-msk"]',0);
        	if(isset($el_abs1)){
       			$time = $el_abs1 -> find('b[class="trlist__cell-pointdata__time"]',0)-> innertext;
                $data = $el_abs1 -> find('span[class="trlist__cell-pointdata__date-sub"]',0)-> innertext;
                $trim = full_trim_new($time)." ".full_trim_new($data);
            //	$trim = full_trim($find);
            	if($trim == ""){
                	$prib_f[] = "";
                }else{
                	$prib_f[] = $trim;
                }
        	}
        }
        

        $el = $row-> find('td[class="cT"]',3);
   
        if(isset($el)){
        	$el = $el->find('div[class="datetime-msk"]',0);
        	if(isset($el)){
       			$time = $el -> find('b[class="trlist__cell-pointdata__time"]',0)-> innertext;
                $data = $el -> find('span[class="trlist__cell-pointdata__date-sub"]',0)-> innertext;
                $trim = full_trim_new($time)." ".full_trim_new($data);
            //	$trim = full_trim($find);
            	if($trim == ""){
                	$otpr[] = "";
                }else{
                	$otpr[] = $trim;
                }
            
        	}
        }
       
        $el_abs = $row-> find('td[class="absrel-abs cT"]',1);
   
        if(isset($el_abs)){
        	$el_abs = $el_abs->find('div[class="datetime-msk"]',0);
        	if(isset($el_abs)){
       			$time = $el_abs -> find('b[class="trlist__cell-pointdata__time"]',0)-> innertext;
                $data = $el_abs -> find('span[class="trlist__cell-pointdata__date-sub"]',0)-> innertext;
                $trim = full_trim_new($time)." ".full_trim_new($data);
            //	$trim = full_trim($find);
            	if($trim == ""){
                	$otpr_f[] = "";
                }else{
                	$otpr_f[] = $trim;
                }
        	}
        }
        
       
        
        
        
        
//         	$el = $row-> find('td[class="absrel-rel cT"]',1);
//         	if(isset($el)){
//        			$find = $el -> find('span',0)-> innertext;
//             	$trim = full_trim($find);
//             	if($trim == ""){
//                 	$otpr[] = "по графику";
//                 }else{
//                 	$otpr[] = $trim;
//                 }
//         	}
        
//         	$el2 = $row-> find('td[class="absrel-rel cT"]',0);
//         	if(isset($el2)){
//        			$find2 = $el2 -> find('span',0)-> innertext;
//             	$trim2 = full_trim($find2);
//             	if($trim2 == ""){
//                 	$prib[] = "по графику";
//                 }else{
//                 	$prib[] = $trim2;
//                 }
               
//         	}
            
        }
    
    		// foreach($row as $el){
    		// if(!$el->find('tr[class="tablo_strow_notRoute"]')){
    		// if($el-> find('td[class="absrel-rel cT"]')){
    		// $find = $row-> find('span',0)-> innertext;
    		// if($find != null){
    		// // $find = str_replace(" ", "", $find);
    		// $res[] = full_trim($find);
    		// 		}else{
    		// //  echo  $row->children(0)-> innertext;
    		// $res[] = 'по графику';
    		// }
    		// }
    		// }
    		// }
    
    		// //if(!$row->find('tr[class="tablo_strow_notRoute"]')){
    		// $find = $row-> find('span',0)-> innertext;
    		// if($find != null){
    		// // $find = str_replace(" ", "", $find);
    		// $res[] = full_trim($find);
    		// 	}else{
    		// //  echo  $row->children(0)-> innertext;
    		// $res[] = 'по графику';
    		// }
    		// // }
    
    
    		// $find = $row-> find('span',0)-> innertext;
    		// if($find != ''){
    		// // $find = str_replace(" ", "", $find);
    		// $res[] = full_trim($find);
    		// }
        }
       
    	
	}

	// foreach($table as $row){
	// if(isset($row)){
	// $find = $row-> find('span',0)-> innertext;
	// if($find != ''){
	// // $find = str_replace(" ", "", $find);
	// $res[] = full_trim($find);
	// }
	// }
	// }
// 	$table = $html->find('table[class="table full-width"]',0) -> find('td[class="absrel-abs cT"]');
// 	foreach($table as $row){
//     	$link = $row -> find('div[class="datetime-msk"]') ;
   
//         if(count($link) != 0){
//     		foreach($link as $a){
//     			$find = $a-> find('b[class="trlist__cell-pointdata__time"]',0)-> plaintext;
//         		$res[] = $find;
    	
//     		}
//         }
    	
// 	}
 $otpr[] = "";
 $otpr_f[] = "";
}else{
	$prib = array();
	$otpr = array();
	$prib_f = array();
	$otpr_f = array();
}


if(count($prib) == 0 && count($otpr)){
	$result = array(  // Формируем массив
            'message' => 'false'
        );

}else{
	$result = array(  // Формируем массив
        	'prib' => $prib,
    		'prib_f' => $prib_f,
    		'otpr' => $otpr,
   			'otpr_f' => $otpr_f,
            'message' => 'true'
        );
}


 		
		return $result;
}


function full_trim($str)                             
{    
	$row = trim(preg_replace('/\s{2,}/', ' ', $str));
  
	$row = str_replace("&nbsp;",' ',$row);
    return $row;
                                                      
}

function full_trim_new($str)                             
{    
	$row = trim(preg_replace('/\s{2,}/', '', $str));
  
	$row = str_replace("&nbsp;",' ',$row);
    $row = str_replace("|",'',$row);
    return $row;
                                                      
}



$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$start = $data['start'];
$end = $data['end'];
$date = $data['date'];
$train = $data['train'];

// $start = $_GET['start'];
// $end = $_GET['end'];
// $date = $_GET['date'];
// $train = $_GET['train'];

if($start != null){
	$url = "https://pass.rzd.ru/tablo/public/ru?STRUCTURE_ID=5199&src_code=".$start."&dst_code=".$end."&date_arr=".$date;

	$result = getTrainLoss($url, $train);
	// echo "<PRE>";
    echo json_encode($result);
	print_r($result);	
	//echo "<PRE>";



	//echo json_encode($result);
}
?>