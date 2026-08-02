<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$host = 'localhost'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'mLXqjmT4K65!!!'; // пароль

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
clearDateBase($link);

function clearDateBase($link)
{
    $query = "SELECT * FROM `rx_train_info` WHERE `recording_date` < DATE_SUB(NOW(), INTERVAL 8 DAY) LIMIT 5";
    $result = mysqli_query($link, $query);
    echo $query."<br>";
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
            $queryDel = "DELETE FROM `rx_train_info` WHERE `id` = ".$id;
            echo $queryDel."<br>";
            mysqli_query($link, $queryDel);
            
             $queryStDel = "DELETE FROM `rx_train_station` WHERE `id_train` <= ".$id;
             echo $queryStDel."<br>";
                    mysqli_query($link, $queryStDel);
        $queryStDel = "DELETE FROM `rx_train_route` WHERE `id_train` <= ".$id;
             echo $queryStDel."<br>";
                    mysqli_query($link, $queryStDel);
            // $querySt = "SELECT * FROM `rx_train_station` WHERE `id_train` = ".$id;
            // $resultSt = mysqli_query($link, $querySt);
            // if ($resultSt) {
                
            //     while ($rowSt = mysqli_fetch_array($resultSt, MYSQLI_ASSOC)) {
                    
            //         $queryStDel = "DELETE FROM `rx_train_station` WHERE `id_train` = ".$id;
            //         mysqli_query($link, $queryStDel);
            //     }
            // }
        }

    }


    $fp = fopen("counter_mar.txt", "a"); // Открываем файл в режиме записи

    $mytext = "Удаление данных в ". date("H:i:s -- d.m.Y")."\r\n"; // Исходная строка
    fwrite($fp, $mytext); // Запись в файл
    echo "Удаление данных в ". date("H:i:s -- d.m.Y");
}


function clearDateBaseWay($link)
{
    $query = "SELECT * FROM `rx_train_way` WHERE `date_create` < DATE_SUB(NOW(), INTERVAL 16 DAY) LIMIT 1";
    $result = mysqli_query($link, $query);
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
            echo $id."<br>";
            $queryDel = "DELETE FROM `rx_train_way` WHERE `id` = ".$id;
            mysqli_query($link, $queryDel);
            $querySt = "SELECT * FROM `rx_train_date` WHERE `id_train` = ".$id;
            echo $querySt."<br>";
            $resultSt = mysqli_query($link, $querySt);
            if ($resultSt) {
                while ($rowSt = mysqli_fetch_array($resultSt, MYSQLI_ASSOC)) {
                    $idSt = $rowSt['id'];
                    echo $idSt."--<br>";
                    $queryStDel = "DELETE FROM `rx_train_date` WHERE `id_train` = ".$id;
                    mysqli_query($link, $queryStDel);
                }
            }
        }

    }


    $fp = fopen("counter_mar.txt", "a"); // Открываем файл в режиме записи
    $mytext = "Удаление данных rx_train_way в ". date("H:i:s -- d.m.Y")."\r\n"; // Исходная строка
    fwrite($fp, $mytext); // Запись в файл
    echo "Удаление данных в ". date("H:i:s -- d.m.Y");
}