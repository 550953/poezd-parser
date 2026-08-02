
 <?php

include 'simple_html_dom.php';



$page = '

<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.344px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1293" class="ui-corner-all" tabindex="-1">883Х, Москва - Калуга</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: none; top: 325px; left: 296.328px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1337" class="ui-corner-all" tabindex="-1">885А, Южный - Акадыр</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1338" class="ui-corner-all" tabindex="-1">885И, Москва - Калуга</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1339" class="ui-corner-all" tabindex="-1">885Х, Алтынколь - Жетиген</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.344px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1350" class="ui-corner-all" tabindex="-1">886А, Акадыр - Южный</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1351" class="ui-corner-all" tabindex="-1">886Х, Жетиген - Алтынколь</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.328px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1362" class="ui-corner-all" tabindex="-1">887А, Южный - Акадыр</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1363" class="ui-corner-all" tabindex="-1">887Х, Александров - Москва</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1364" class="ui-corner-all" tabindex="-1">887Ш, Коростнь - Винница</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.344px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1375" class="ui-corner-all" tabindex="-1">888А, Акадыр - Южный</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1376" class="ui-corner-all" tabindex="-1">888Х, Москва - Александров</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.328px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1387" class="ui-corner-all" tabindex="-1">889И, Алексеевка - Павлодар</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1388" class="ui-corner-all" tabindex="-1">889К, Винница - Коростнь</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.328px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1421" class="ui-corner-all" tabindex="-1">890Х, Павлодар - Кулунда</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1422" class="ui-corner-all" tabindex="-1">891Г, Владимир - Москва</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1423" class="ui-corner-all" tabindex="-1">891И, Шалкар - Актобе</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1424" class="ui-corner-all" tabindex="-1">891Х, Калуга - Москва</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1425" class="ui-corner-all" tabindex="-1">892И, Актобе - Шалкар</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1426" class="ui-corner-all" tabindex="-1">892Х, Москва - Владимир</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1427" class="ui-corner-all" tabindex="-1">893И, Калуга - Москва</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1428" class="ui-corner-all" tabindex="-1">893Х, Болашак - Жанаозен</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1429" class="ui-corner-all" tabindex="-1">894М, Москва - Владимир</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1430" class="ui-corner-all" tabindex="-1">894Х, Жанаозен - Болашак</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.344px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1431" class="ui-corner-all" tabindex="-1">895А, Павлодар - Курчатов</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1432" class="ui-corner-all" tabindex="-1">895Г, Курчатов - Павлодар</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1433" class="ui-corner-all" tabindex="-1">895Х, Калуга - Москва</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.328px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1454" class="ui-corner-all" tabindex="-1">897Л, Киев - Киев</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1455" class="ui-corner-all" tabindex="-1">897М, Александров - Москва</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1456" class="ui-corner-all" tabindex="-1">897Х, Тобыл - Карталы 1</a></li></ul>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 3; display: block; top: 325px; left: 296.344px; width: 296px;"><li class="ui-menu-item" role="presentation"><a id="ui-id-1490" class="ui-corner-all" tabindex="-1">898М, Москва - Александров</a></li><li class="ui-menu-item" role="presentation"><a id="ui-id-1491" class="ui-corner-all" tabindex="-1">898Х, Аксу - Тобыл</a></li></ul>



';
$html = str_get_html($page);
        if($html->find('li a')){
        
           
            foreach($html->find('li a') as $div){
                echo " \"".$div->innertext."\",<br>";
            }
            
            
        }else{
           $err = 1; 
        }
     
          if($err != 0){
             $link = array('messege' => 'false');
          }

    
     
     ?>