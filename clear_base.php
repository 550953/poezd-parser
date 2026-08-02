<?php
$host = 'localhost'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'mLXqjmT4K65!!!'; // пароль

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
clearDateBase($link);

function clearDateBase($link)
{
    $query = "SELECT * FROM `mn_way_info` WHERE `date_create` < DATE_SUB(NOW(), INTERVAL 1 DAY) LIMIT 20";
    $result = mysqli_query($link, $query);
    $i = 0;
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $i++;
            $id = $row['id'];
            $query_info = "DELETE FROM `mn_pass_info` WHERE `id_info`=".$id;
            mysqli_query($link, $query_info);
            $query_info = "DELETE FROM `mn_way_info` WHERE `id`=".$id;
            mysqli_query($link, $query_info);

//             $result_info = mysqli_query($link, $query_info);
//             while ($row_info = mysqli_fetch_array($result_info, MYSQLI_ASSOC)) {
//                 echo "INFO ".$id." PASS ".$row_info['seats']."<br>";
//             }

        }
       
        $fp = fopen("counter.txt", "a"); // Открываем файл в режиме записи
        $mytext = "Удаление данных в ". date("H:i:s -- d.m.Y")."\r\n count ".$i; // Исходная строка
        fwrite($fp, $mytext); // Запись в файл
        echo "Удаление данных в ". date("H:i:s -- d.m.Y"). "count ".$i;
        
    }
}

mysqli_close($link);
?>