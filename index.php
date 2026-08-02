
    <?php

include 'simple_html_dom.php';
include 'phpQuery.php';

$train = $_GET['train'];
$mar = $_GET['mar'];

function get_web_page( $url )
{
  $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

  $ch = curl_init( $url );
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
  curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
  curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
  curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
  curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа

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
if($train != null){  // Получение ссылок на страницу маршрута и годового графика
     $url = "https://www.tutu.ru/poezda/search_train.php?train=".$train;
     $result = get_web_page( $url );
     $err = 0;
     if ( $result['errno'] != 0 )
        { //... ошибка: неправильный url, таймаут, зацикливание ... обработать по желанию
         $result = get_web_page( $url );
     $err = 1;
     }
    
      if ( $result['http_code'] != 200 )
        { //... ошибка: нет страницы, нет прав ... обработать по желанию
     $err = 1;
     }
    
    $page = $result['content'];

    $html = str_get_html($page);
        if($html->find('table[id=train_list_table] div[class="reviews"] ul li a')){
        
           $i=0;
            foreach($html->find('div[class="reviews"] ul li a') as $div){
                $del = array("/poezda/");
                if($i==0 || $i== count($html->find('div[class="reviews"] ul li a'))-1){
                 $arr[] =  str_replace($del,"",$div->href);
                }
                $i++;
            }
            $link['link'] = $arr; 
            
        }else{
           $err = 1; 
        }
     
          if($err != 0){
             $link = array('messege' => 'false');
          }
          
    echo json_encode($link);
    
     print_r($data->innertext);
 
}

if($mar != null){   // Парсинг маршрута поезда
    $url = "https://www.tutu.ru/poezda/".$mar;
     $result = get_web_page( $url );
     $err = 0;
     if ( $result['errno'] != 0 )
        { //... ошибка: неправильный url, таймаут, зацикливание ... обработать по желанию
         $result = get_web_page( $url );
     $err = 1;
     }
    
      if ( $result['http_code'] != 200 )
        { //... ошибка: нет страницы, нет прав ... обработать по желанию
     $err = 1;
     }
    
    $page = $result['content'];
   // print_r($page);
    
    $html = str_get_html($page);
        if($html->find('tr[class="select"]')){
            // $i = 0;
            // foreach($html->find('table[class=route_table] tr[class=select] td[class=flag] a')as $div){
            
            //   if($i != 0 && $i != count($html->find('tr[class=select] td[class="flag"] a')) - 1){
            //  // echo $div->innertext;
            //         $arr[] = trim($div->innertext, " \t.");
            //   }
            //   $i++;
            // }
            // $link['name'] = $arr; 
            //  $i = 0;
            // foreach($html->find('table[class=route_table] tr[class=select] td[class!=flag]')as $div){
            
            //   if($i != 0 && $i != count($html->find('tr[class=select] td[class!="flag"]')) - 1){
            //   echo $div->plaintext;
            //         $arr1[] = trim($div->plaintext, $character_mask = " \t\n\r\0\x0B");
            //   }
            //   $i++;
            // }
            // $link['prib'] = $arr1; 
             foreach($html->find('script,link,comment,span,noscript') as $tmp)$tmp->outertext = '';
             foreach($html->find('tr[class$=flag]') as $tmp)$tmp->outertext = '';
             $i = 0;
             foreach($html->find('tr[class=select] td[class=flag] a') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select] td[class=flag] a')) - 1){
                  $arr[] = trim($tmp->innertext, " \t.");
                 }
                 $i++;
              }
              $i = 0;
              foreach($html->find('tr[class=select]') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select]')) - 1){
                  //  echo $tmp->children(3)->innertext;
                  $arr1[] = trim($tmp->children(3)->innertext, " \t.");
                 }
                 $i++;
              }
              $i = 0;
              foreach($html->find('tr[class=select]') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select]')) - 1){
                  //  echo $tmp->children(4)->innertext;
                  $arr2[] = trim($tmp->children(4)->innertext, " \t.");
                 }
                 $i++;
              }
              $i = 0;
              foreach($html->find('tr[class=select]') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select]')) - 1){
                  //  echo $tmp->children(5)->innertext;
                  $arr3[] = trim($tmp->children(5)->innertext, " \t.");
                 }
                 $i++;
              }
              $i = 0;
              foreach($html->find('tr[class=select]') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select]')) - 1){
                  //  echo $tmp->children(6)->innertext;
                  $arr4[] = trim($tmp->children(6)->innertext, " \t.");
                 }
                 $i++;
              }
              $i = 0;
             foreach($html->find('tr[class=select] td.routetime div.routetime-full') as $tmp){
                 if($i != 0 && $i != count($html->find('tr[class=select]')) - 1){
                  //  echo $tmp->innertext;
                  $arr5[] = trim($tmp->innertext, " \t.");
                 }
                 $i++;
              }
           //  echo $html->innertext;
            //echo count($html->find('tr[class=select] td[class=flag] a'));
            // $m = $html->find('div#route-table');
            // echo $m->innertext;
            //  foreach($html->find('table[class=route_table] tr[class=select] td[class=flag] a')as $div){
            //      $arr[] = trim($div->innertext, " \t.");
            //  }
             $link['name'] = $arr;
             $link['prib'] = $arr1; 
             $link['stay'] = $arr2; 
             $link['otpr'] = $arr3;
             $link['km'] = $arr4;
             $link['time'] = $arr5;
             //$data[] = $link;
            
        }else{
           $err = 1; 
        }
     
          if($err != 0){
             $link = array('messege' => 'false');
          }
          
    echo json_encode($link,SON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

?>

