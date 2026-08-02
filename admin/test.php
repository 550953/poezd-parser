<?php

// echo stristr($email, 'e'); // Выводит: ER@EXAMPLE.com
// echo stristr($email, 'e', true); // Выводит: US
$str = "Здравствуйте. Продлите мне подписку.
Мой UID/email:
\"baksys@ya.ru\"
Чек сейчас пришлю:";


$str = str_replace('"', '', $str);
$str = stristr($str, ':'); 
$str = stristr($str, 'Ч', true); 
$str = str_replace(':', '', $str);
echo $str;
?>