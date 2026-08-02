    <?php

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

//$all_mass = array('messege' => 'false');
//$train = $_GET['train'];

function getTrain($train){
    if ($train != null) {  // ÐÐ¾Ð»ÑÑÐµÐ½Ð¸Ðµ ÑÑÑÐ»Ð¾Ðº Ð½Ð° ÑÑÑÐ°Ð½Ð¸ÑÑ Ð¼Ð°ÑÑÑÑÑÐ° Ð¸ Ð³Ð¾Ð´Ð¾Ð²Ð¾Ð³Ð¾ Ð³ÑÐ°ÑÐ¸ÐºÐ°
        $url = "https://www.tutu.ru/poezda/search_train.php?train=" . $train;
        $result = get_web_page($url);
        $err = 0;
        if ($result['errno'] != 0) { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÐ¿ÑÐ°Ð²Ð¸Ð»ÑÐ½ÑÐ¹ url, ÑÐ°Ð¹Ð¼Ð°ÑÑ, Ð·Ð°ÑÐ¸ÐºÐ»Ð¸Ð²Ð°Ð½Ð¸Ðµ ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
            $result = get_web_page($url);
            $err = 1;
        }

        if ($result['http_code'] != 200) { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÑ ÑÑÑÐ°Ð½Ð¸ÑÑ, Ð½ÐµÑ Ð¿ÑÐ°Ð² ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
            $err = 1;
        }

        $page = $result['content'];

        $html = str_get_html($page);
        if ($html->find('table[id=train_list_table] tr')) {
            // echo count($html->find('table[id=train_list_table] tr'));
            foreach ($html->find('table[id=train_list_table] tr') as $divs) {
                //  echo count($divs -> find('p[class="trailer"]'));
                if (!$divs->find('p[class="trailer"]')) {
                    if ($divs->find('div[class="reviews"]')) {
                        //    echo count($divs->find('div[class="reviews"]',0) ->find ('ul li a'))."<br>";
                        foreach ($divs->find('div[class="reviews"]', 0)->find('ul li a') as $divss) {
                            $del = array("/poezda/");
                            if ($i == 0 || $i == count($divs->find('div[class="reviews"]', 0)->find('ul li a')) - 1) {
                                $arr[] = str_replace($del, "", $divss->href);

                            }
                            $i++;
                        }
                        $link['link'] = $arr;
                    }
                }
            }
        }


        //     if($html->find('table[id=train_list_table] div[class="reviews"]')){
        //   //  echo count($html->find('div[class="reviews"]',0) ->find ('ul li a'))."<br>";
        //       $i=0;
        //         foreach($html->find('div[class="reviews"]',0) ->find ('ul li a') as $div){
        //             $del = array("/poezda/");
        //             if($i==0 || $i== count($html->find('div[class="reviews"]',0) ->find ('ul li a'))-1){
        //              $arr[] =  str_replace($del,"",$div->href);

        //             }
        //             $i++;
        //         }
        //         $link['link'] = $arr;

        //     }else{
        //       $err = 1;
        //     }


        if ($err != 0) {
            $all_mass = array('messege' => 'false');
        }

        //echo json_encode($link);
        // echo "<pre>";
        // print_r($link['link'][0]);
        // echo "<pre>";
        if (err == 0) {
            $mar = $link['link'][1];
            // if($train == "380У"){
            //     $mar = "view_times.php?np=e2bb9b";
            // }
        } else {
            echo json_encode($link);
        }
        //  echo $mar;
    }

//$mar = $_GET['mar'];
    if ($mar != null) {   // ÐÐ°ÑÑÐ¸Ð½Ð³ Ð¼Ð°ÑÑÑÑÑÐ° Ð¿Ð¾ÐµÐ·Ð´Ð°
        $url = "https://www.tutu.ru/poezda/" . $mar;
        $result = get_web_page($url);
        $err = 0;
        if ($result['errno'] != 0)
            echo "Ð½ÐµÐ¿ÑÐ°Ð²Ð¸Ð»ÑÐ½ÑÐ¹ url";
        { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÐ¿ÑÐ°Ð²Ð¸Ð»ÑÐ½ÑÐ¹ url, ÑÐ°Ð¹Ð¼Ð°ÑÑ, Ð·Ð°ÑÐ¸ÐºÐ»Ð¸Ð²Ð°Ð½Ð¸Ðµ ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
            $result = get_web_page($url);
            $err = 1;
        }

        if ($result['http_code'] != 200) { //... Ð¾ÑÐ¸Ð±ÐºÐ°: Ð½ÐµÑ ÑÑÑÐ°Ð½Ð¸ÑÑ, Ð½ÐµÑ Ð¿ÑÐ°Ð² ... Ð¾Ð±ÑÐ°Ð±Ð¾ÑÐ°ÑÑ Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ñ
            echo "Ð½ÐµÑ ÑÑÑÐ°Ð½Ð¸ÑÑ";
            $err = 1;
        }

        $page = $result['content'];

        //  print_r($page);
        $html = str_get_html($page);

        //foreach($html->find('script,link,comment') as $htmls)$htmls->outertext = '';

        foreach ($html->find('table[class="graph_table"] td[data-date]') as $tmp) {
            //  echo $tmp->attr['data-date'];

            $findme = 'never_date';
            $mystring = $tmp->attr['class'];
            $pos = stripos($mystring, $findme);
            if ($pos !== false) {
                $day = "false";
            } else {
                $day = "true";
            }
            $date = $tmp->attr['data-date'];
            $result_date[] = array(
                "date" => $date,
                "value" => $day
            );

//
//     $find = 'last_date';
//    $string = $tmp->attr['class'];
//    $poster = stripos($string, $find);
//        if ($poster === false) {
//            $find = 'never_date';
//            $string = $tmp->attr['class'];
//            $poster = stripos($string, $find);
//            if ($poster === false) {
//                $days[] = $tmp->attr['data-date'];
//            }
//        }
//
//
//    }
//
//    $actual_day = $days[0];
//    //  $res = array();
//    // for($i=0;$i < count($date); $i++){
//    //     $res[] =  array(
//    //         "date" => $date[$i],
//    //         "value" => $day[$i]
//    //     );
//    //  }
//    // print_r($res);
//    //  echo json_encode($res,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
// include '../examples/train_station_list.php';
// //echo "actual_day = ".$train;
// for($i=0;  $i<10;$i++){
//     $arr_station = get_station($actual_day,$train);
//     if(json_decode($arr_station,true)[train] == null){
//        $all_mass = array('messege' => 'false');
//       // echo "NULL";
//    }else{
//        $array = json_decode($arr_station,true);
//      //  echo "NOT NULL";
//        break;
//    }
// }
//
//    $all_station_arr = $array['routes']['Stop'];
//    if($all_station_arr == null){
//        $all_station_arr = $array['routes'][0]['Stop'];
//    }
//    // echo "<pre>";
//    // print_r ($all_station_arr);
//    // echo "<pre>";
//    //  echo "<br>";
//    $dist_old = 2;
//    foreach($all_station_arr as $station){
//        // echo "<pre>";
//        // print_r ($station);
//        // echo "<pre>";
//        $dist = $station['Distance'];
//       // echo $dist."  ".$dist_old;
//        if($station['Sign'] == null && $dist_old != $dist){
//            $dist_old = $dist;
//
//            $name = $station['@attributes']['Station'];
//            $code = $station['@attributes']['Code'];
//            $prib = $station['ArvTime'];
//            $wait = $station['WaitingTime'];
//            $otpr = $station['DepTime'];
//            $dist = $station['Distance'];
//            $day = $station['Days'];
//
//            $result_arr[] =  array(
//            "name" => $name,
//            "code" => $code,
//            "prib" => $prib,
//            "wait" => $wait,
//            "otpr" => $otpr,
//            "dist" => $dist,
//            "day" => $day
//            );
//
//
//            // echo $station['@attributes']['Station'];
//            // echo "<br>";
//        }
//    }
//    $all_mass = array(
//        "dates" => $result_date,
//        "stations" => $result_arr
//        );
//
//    //  echo "<pre>";
//    // print_r ($all_mass);
//    // echo "<pre>";
        }

    }
    return $result_date;
}
//$all_mass = getTrain($train);
//include './train_station_list.php';
//echo json_encode($all_mass);



?>