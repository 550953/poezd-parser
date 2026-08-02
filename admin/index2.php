<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once '../api/connection.php'; // подключаем скрипт

require "fire_base/vendor/autoload.php";
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
const DEFAULT_URL = 'https://train-9b5c9.firebaseio.com/';
const DEFAULT_TOKEN = 'fm1GhKwuXophcjJW2kzvxtiDw8JHkJ525FTKvdHT';
const PATH_MESSAGES = '/messages/';
const PATH_CHATS = '/chats';
const UUID_ADMIN = 'vpp9CXouhwMBkn6kYIlmcXVEgrA3';

function getUserInfo($uid, $link){
    $query = "SELECT * FROM `users` WHERE `uid`='".$uid."'";
    $name = "";
    $email = "";
    $result = mysqli_query($link, $query);
    if($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $name = $row['name'];
            $email = $row['email'];
        }
    }
    if($name == ""){
        return $email;
    }else{
        return $name;
    }

}


function setInfoChat($uid, $count, $link){
    $query = "INSERT INTO `chats_info`(`id`, `uid`, `count`) VALUES (null,'".$uid."',".$count.")";
    mysqli_query($link, $query);
}
function getCountInfoChat($uid, $link){
    $count = 0;
    $query = "SELECT `count` FROM `chats_info` WHERE `uid` = '".$uid."'";
    $result = mysqli_query($link, $query);
    if($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $count = $row['count'];
        }
    }

    return $count;
}
function updateInfoChat($uid, $count, $link){

    $query = "UPDATE `chats_info` SET `count` = ".$count." WHERE `uid` = ".$uid;
    mysqli_query($link, $query);
}


function getMessage($firebase, $key){
    $messages = $firebase->get(PATH_MESSAGES . $key);
    $arrayMessages = json_decode($messages,true);

    if($arrayMessages != null) {
        foreach ($arrayMessages as $message) {
            $result[] = array(
                'message' => $message['message'] ,
                'sender' =>  $message['sender'] ,
                'timestamp' => $message['timestamp'] ,
                'uidMessage' => $message['uidMessage'],
                'uidRoom' =>  $message['uidRoom'],
                'delete' =>  $message['delete']
            );
        }
    }
    return $result;
}

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Админка</title>

    <link rel="stylesheet" href="style.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
     <script type="text/javascript" src="./scripts/script.js"></script>
</head>
<body>
<div class="content">
    <div class="block_menu">
<ul class="metro">
    <h3 class="widget-title">Меню</h3>
    <li><a href="">Информация по поездам</a>
        <ul>
            <li><a href=?year_way=true>Годовые графики поездов</a></li>
            <li><a href=?marshrut=true>Маршруты поездов</a></li>
            <li><a href=?monitoring=true>Мониторинг мест пассажиров</a></li>
        </ul>
    </li>
    <li><a href=>Пользователи</a>
        <ul>
            <li><a href=?user=true>Список пользователей</a></li>
            <li><a href=?message=true>Сообщения</a></li>
            <li><a href=?chats=true>Чаты с пользователями</a></li>
            <li><a href=?send_mail=true>Оповестить пользователей</a></li>

        </ul>
    </li>
    <li><a href=>База данных</a>
        <ul>
            <li><a href=?base=true>Состояние базы данных</a></li>
        </ul>
    </li>
    
    
     <li><a href=>Список поездов</a>
        <ul>
            <li><a href=?train_list=true>Полный список поездов</a></li>
        </ul>
    </li>

</ul>
    </div>
    
    <div class="frame">
    <div class="add_part">
    </div>
    </div>
    <div class="block_content">

        <?php




        if($_GET['chats'] == "true"){

            $firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);
            $chats = $firebase->get(PATH_CHATS);
            $chatsArray = json_decode($chats,true);
            if($chatsArray != null){
                foreach ($chatsArray as $chat){
                    $user_arr = array_values($chat);
                    foreach ($user_arr as $s){
                        $user = $s;
                    }
                    $key_chat_arr = array_keys($chat);
                    foreach ($key_chat_arr as $s){
                        $key = $s;
                    }
                    $chat_[] = array(
                        "key" => $key,
                        "user" => $user
                    );
//
                }
            }

            ?>

        <table style="width: 70%;">
            <tbody>

            <tr>
                <th></th>
                <th colspan="3">Список чатов с пользователями</th>
            </tr>

            <?php
            $i = 0;
            foreach ($chat_ as $value){
                $newMessage = false;
                $message = getMessage($firebase, $value['key']);

                $userInfo = getUserInfo($value['user'],$link);
                if(count($message) > 0) {

                    if(getCountInfoChat($value['user'], $link) == 0){
                        setInfoChat($value['user'], count($message), $link);
                    }else{
                        if(getCountInfoChat($value['user'], $link) < count($message)){
                            $newMessage = true;
                        }
                    }


                    $i++;
                    ?>
                    <tr>
                        <td><? echo $i; ?></td>
                        <td> Чат с пользователем <? echo $userInfo; ?><?if($newMessage) echo " (Есть новые сообщения!) "?></td>
                        <td>
                            <button class="chat_button" name="<? echo $value['user']; ?>" value="chat<? echo $i; ?>" id="<? echo count($message); ?>">Подробнее</button>
                        </td>
                        <td>
                            <button class="refresh" id="<? echo $i; ?>" value="<? echo $value['key']; ?>"
                                    name="<? echo $value['user']; ?>">Обновить
                            </button>
                        </td>
                    </tr>
                    <tr id="chat<? echo $i; ?>" style="display: none">
                        <td colspan="4">
                            <div id="chatvalue<? echo $i; ?>">

                                <?php
                                foreach ($message as $mess) {
                                    if ($mess['sender'] == UUID_ADMIN) {
                                        ?>

                                        <div class="item">
                                            <div class="message_user"><? echo getUserInfo($mess['sender'], $link); ?></div>
                                            <div class="message"><? echo $mess['message']; ?></div>
                                            <div class="message_time"><?php echo date("H:i:s d.m.Y", $mess['timestamp'] / 1000); ?></div>
                                            <div class="message_delete">
                                                <button class="delete_mess" name="<? echo $value['key']; ?>"
                                                        id="<? echo $i; ?>" value="<? echo $mess['uidMessage']; ?>">
                                                    Удалить
                                                </button>
                                            </div>
                                        </div>

                                        <?php
                                    } else {
                                        ?>

                                        <div class="item">
                                            <div class="message_user"><? echo getUserInfo($mess['sender'], $link); ?></div>
                                            <div class="message"><? echo $mess['message']; ?></div>
                                            <div class="message_time"><?php echo date("H:i:s d.m.Y", $mess['timestamp'] / 1000);
                                                if ($mess['delete'] == true) {
                                                    echo " (удалено пользователем)";
                                                } ?></div>
                                        </div>

                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="answer">
                                <textarea style="width: 100%;" class="mess_sender<? echo $i; ?>" cols="100"
                                          rows="10"></textarea>
                                <input type="submit" id="<? echo $i; ?>" class="send_mess"
                                       name="<? echo $value['key']; ?>" value="Отправить">
                            </div>
                        </td>
                    </tr>


                    <?php
                }

            }


            ?>

            </tbody></table>



            <?php

        }
        ?>

        <script type="text/javascript">

            $(document).on('click', '.send_mess', function(e){
                var key = $(this).attr('name');
                var index = $(this).attr('id');
                var message = $('.mess_sender' + index).val();
                $.ajax({
                    type: "POST",
                    url: "sendmessage.php",
                    data: {
                        "key" : key,
                        "message" : message,
                        "index" : index
                    },
                    success: function(response) {
                        $('.mess_sender' + index).attr('value', '');
                        $('#chatvalue' + index + ' .item').remove();
                        $('#chatvalue' + index).append(response);
                        console.log(response);
                        //refreshMessage();
                    }
                });
            });




            $(document).on('click', '.delete_mess', function(e) {
                var key_mess = $(this).attr('value');
                var key = $(this).attr('name');
                var index = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "delete_message.php",
                    data: {
                        "key_mess" : key_mess,
                        "key" : key,
                        "index" : index
                    },
                    success: function(response) {
                        $('#chatvalue' + index + ' .item').remove();
                        $('#chatvalue' + index).append(response);

                        console.log(response);
                    }
                });

            });
            $(document).on('click', '.refresh', function(e) {
                var key = $(this).attr('value');
                var index = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "refresh.php",
                    data: {
                        "chats": true,
                        "key" : key,
                        "index" : index
                    },
                    success: function(response) {
                        $('#chatvalue' + index + ' .item').remove();
                        $('#chatvalue' + index).append(response);

                        console.log(response);
                    }
                });
            });


        </script>
        <script type="text/javascript">

            $(document).on('click', '.chat_button', function(e) {

                var count = $(this).attr('id');
                var uid = $(this).attr('name');

                var cl = $(this).attr('value');
                var n = $("#" + cl).css("display");
                if(n == "none"){
                    $("#" + cl).css("display", "");
                }else {
                    $("#" + cl).css("display", "none");
                }

                $.ajax({
                    type: "POST",
                    url: "readnew.php",
                    data: {
                        "count": count,
                        "uid" : uid
                    },
                    success: function(response) {

                        console.log(response);
                    }
                });
            });
        </script>

        <?php
        if($_GET['send_mail'] == "true") {   // Оповещение пользователей
            ?>
            <table style="width: 70%;">
                <tbody>

                <tr>
                    <th>Отправка сообщения пользователям на почту</th>
                </tr>
                <tr>
                    <th>Cообщение:</th>
                </tr>
                <tr>
                    <th>
                        <textarea name="comment" cols="100" rows="10"></textarea>
                    </th>
                </tr>
                <tr>
                    <th></th>
                </tr>
                <tr>
                    <th><button class="send" type="submit" name="id"?>Отправить</button></th>
                </tr>
                </tbody>
            </table>
            <?php
        } // Оповещение пользователей
        ?>



        <script type="text/javascript">
            $('.send').click(function() {

                var message = $('textarea[name="comment"]').val();
                $.ajax({
                    type: "POST",
                    url: "send_all_user.php",
                    data: {
                        message: message
                      },
                    success: function() {
                        alert("Сообщение отправлено!");
                        location.reload();
                    }
                });
            });
        </script>




        <?php
        if($_GET['base'] == "true") {   // Данные базы
            ?>
        <table style="width: 70%;">
            <tbody>
                <tr>
                    <th colspan="3">Информация о количестве не используемых записей в базе данных </th>
                </tr>

                <?php

                $query = "SELECT * FROM `mn_way_info` WHERE `date_create` < DATE_SUB(NOW(), INTERVAL 8 DAY)";
                $result = mysqli_num_rows(mysqli_query($link, $query));

                ?>

                <tr>
                    <td class="com">Количество записей:</td>
                    <td class="com"><?php echo $result;?></td>
                    <td class="com"><button class="delete" type="submit">Удалить 100 записей</button></td>
                </tr>
            </tbody>
        </table>
            <?php                //Данные базы
        }
        ?>



        <script type="text/javascript">
            $('.delete').click(function() {

                var day = $('input[name="day"]').val();
                $.ajax({
                    type: "POST",
                    url: "clear_base.php",
                    success: function() {
                        alert("Записи удалены!");
                        location.reload();
                    }
                });
            });
        </script>


    <?php
    if($_GET['year_way'] == "true"){   // Список годовых графиков

        ?>

<table>
    <tbody>
        <tr>
            <th>№</th>
            <th>Номер поезда</th>
            <th>Полное название поезда</th>
            <th>Дата записи в базу</th>
            <th></th>
        </tr>
        <?php


        $query = "SELECT * FROM `rx_train_way` ORDER BY `id` DESC";
        $result = mysqli_query($link, $query);

        $i = 1;
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = $row['id'];
                $number = $row['number'];
                $full_name = $row['full_name'];
                $date_create = $row['date_create'];
                ?>
                <tr>
                    <td class="com"><?php echo $i; ?></td>
                    <td class="com"><?php echo $number; ?></td>
                    <td class="com"><?php echo $full_name; ?></td>
                    <td class="com"><?php echo $date_create; ?></td>
                    <td class="com">
                        <form method="post" action="delete_year_way.php">
                            <button type="submit" name="id" value="<?=$id; ?>">
                                Удалить</button></form>

                    </td>
                </tr>

    <?php
                $i++;

            }
        }
?>
    </tbody>
</table>
<?php

    }   // Список годовых графиков
?>




<?php
if($_GET['marshrut'] == "true"){   // Список маршрутов

    ?>
    <table>
        <tbody>
        <tr>
            <th>№</th>
            <th>Номер поезда</th>
            <th>Полное название поезда</th>
            <th>Дата выезда</th>
            <th>Дата записи в базу</th>
            <th></th>
        </tr>
        <?php


        $query = "SELECT * FROM `rx_train_info` ORDER BY `id` DESC";
        $result = mysqli_query($link, $query);

        $i = 1;
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = $row['id'];
                $number = $row['number'];
                $full_name = $row['full_name'];
                $date_start = $row['date_start'];
                $date_create = $row['recording_date'];
                ?>
                <tr>
                    <td class="com"><?php echo $i; ?></td>
                    <td class="com"><?php echo $number; ?></td>
                    <td class="com"><?php echo $full_name; ?></td>
                    <td class="com"><?php echo $date_start; ?></td>
                    <td class="com"><?php echo $date_create; ?></td>
                    <td class="com">
                        <form method="post" action="delete_marshrut.php">
                            <button type="submit" name="id" value="<?=$id; ?>">
                                Удалить</button></form>
                    </td>
                </tr>

                <?php
                $i++;

            }
        }
        ?>
        </tbody>
    </table>
    <?php

}   // Список маршрутов
?>


<?php
if($_GET['monitoring'] == "true"){   // Список пассажиров

    ?>
    <table>
        <tbody>
        <tr>
            <th>№</th>
            <th>Номер поезда</th>
            <th>Дата выезда</th>
            <th>Дата записи в базу</th>
            <th></th>
        </tr>
        <?php


        $query = "SELECT * FROM `mn_way_info`  ORDER BY `id` ASC";
        $result = mysqli_query($link, $query);

        $i = 1;
        $number = "";
        $date_start = "";
        if($result){
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {



                if(($number != $row['train_name'] && $date_start != $row['date_start']) || $number == $row['train_name'] && $date_start != $row['date_start']) {

                    $id = $row['id'];
                    $number = $row['train_name'];
                    $date_start = $row['date_start'];
                    $date_create = $row['date_create'];


                    ?>
                    <tr>
                        <td class="com"><?php echo $i; ?></td>
                        <td class="com"><?php echo $number; ?></td>
                        <td class="com"><?php echo $date_start; ?></td>
                        <td class="com"><?php echo $date_create; ?></td>
                        <td class="com">
                            <form method="post" action="delete_monitoring.php">
                                <input type="hidden" name="date_start" value="<?= $date_start; ?>" >
                                <button type="submit" name="number" value="<?= $number; ?>">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>

                    <?php
                    $i++;
                }

            }
        }
        ?>
        </tbody>
    </table>
    <?php

}   // Список пассажиров
?>
 <?php
        if($_GET['user'] == "true"){   // Список пользователей

            ?>
            <table>
                <tbody>
                    
                <tr>
                    <form action="" method="post">  
                    <th>Поиск</th>
                    <th colspan=2><input type="text" placeholder="Введите email"name="email_search" size="100"></th>
                    <th><button type="submit" name="search" value="">Искать</button></th>
                    <th><button type="submit" name="clear" value="">Очистить</button></th>
                    </form>
                
                </tr>
                <tr>
                    <th>№</th>
                    <th>Имя</th>
                    <th>E-mail</th>
                    <th>Дата регистрации</th>
                    <th></th>
                </tr>
                
              
                <?php
                
                $per_page=300;
                if (isset($_GET['page'])){ 
                    $page=($_GET['page']-1); 
                }else {
                    $page=0;
                }
                if (!empty($_REQUEST['email_search'])) {
                    $query_all = "SELECT * FROM users WHERE email LIKE '%".$_REQUEST['email_search']."%'";
  
                }else{
                    $query_all = "SELECT * FROM `users`";
                    $result = mysqli_query($link,$query_all);
                $total_rows = mysqli_num_rows($result);
                $num_pages = ceil($total_rows/$per_page);
                for($i=1;$i<=$num_pages;$i++) {
                  if ($i-1 == $page) {
                    echo '<a class="page_non" href="">'.$i."</a> ";
                  } else {
                    echo '<a class="page" href="'.$_SERVER['PHP_SELF'].'?user=true&page='.$i.'">'.$i."</a> ";
                  }
                }
                }
               // $query = "SELECT * FROM `users`";
                


                $start=abs($page*$per_page);
                if (!empty($_REQUEST['email_search'])) {
                    $query = "SELECT * FROM `users` WHERE email LIKE '%".$_REQUEST['email_search']."%' ORDER BY `id` DESC LIMIT $start,$per_page";
                }else{
                    $query = "SELECT * FROM `users` ORDER BY `id` DESC LIMIT $start,$per_page";
                }
                             
                             

              //  $query = "SELECT * FROM `users` ORDER BY `id` DESC LIMIT $start,$per_page";
                $result = mysqli_query($link, $query);

                $i = 1;
                if($result){
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $email = $row['email'];
                        $date_create = $row['date_create'];
                        $date = date("H:i:s -- d.m.Y", $date_create/1000);
                        ?>
                        <tr>
                            <td class="com"><?php echo $i; ?></td>
                            <td class="com"><?php echo $name; ?></td>
                            <td class="com"><?php echo $email; ?></td>
                            <td class="com"><?php echo $date; ?></td>
                            <td class="com">
                                <a href="/parse/admin/users/user_info.php?id=<?=$id; ?>">Подробнее</a>
                            </td>
                        </tr>

                        <?php
                        $i++;

                    }
                }
                ?>
                </tbody>
            </table>
            <?php

        }   // Список пассажиров
        ?>


        <?php
        if($_GET['message'] == "true"){   // сообщений пассажиров

            ?>
            <table>
                <tbody>
                <tr>
                    <th>№</th>
                    <th>Имя</th>
                    <th>E-mail</th>
                    <th></th>
                </tr>
                <?php


                $query = "SELECT * FROM `email` ORDER BY `id` DESC";
                $result = mysqli_query($link, $query);

                $i = 1;
                if($result){
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $email = $row['email'];
                        $message = $row['message'];
                        $vagon = $row['vagon'];
                        $train = $row['train'];
                        $date = $row['date'];

                        ?>
                        <tr>
                            <td class="com"><?php echo $i; ?></td>
                            <td class="com"><?php echo $name; ?></td>
                            <td class="com"><?php echo $email; ?></td>
                            <td class="com">
                                <a href="/parse/admin/users/mail_info.php?id=<?=$id; ?>">Подробнее</a>
                            </td>
                        </tr>

                        <?php
                        $i++;

                    }
                }
                ?>
                </tbody>
            </table>
            <?php

        }   // Список сообщений
        ?>
        
        
        
        
        
        <?php
        if($_GET['train_list'] == "true"){   // список поездов 
        
        
    $per_page=100;
    if (isset($_GET['page'])){ 
        $page=($_GET['page']-1); 
    }else {
        $page=0;
    }
    
    $query = "SELECT * FROM `trains_list`";
    $result = mysqli_query($link,$query);
    $total_rows = mysqli_num_rows($result);
    $num_pages = ceil($total_rows/$per_page);
    for($i=1;$i<=$num_pages;$i++) {
      if ($i-1 == $page) {
        echo '<a class="page_non" href="">'.$i."</a> ";
      } else {
        echo '<a class="page" href="'.$_SERVER['PHP_SELF'].'?train_list=true&page='.$i.'">'.$i."</a> ";
      }
    }
    

            ?>
            <table>
                <tbody>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th></th>
               
                </tr>
                <?php
                
                $i = 1;
                        // количество записей, выводимых на странице
            
            // получаем номер страницы
            
            // вычисляем первый оператор для LIMIT
            $start=abs($page*$per_page);
            // составляем запрос и выводим записи
            // переменную $start используем, как нумератор записей.
            $query="SELECT * FROM `trains_list` ORDER BY `name_train` ASC LIMIT $start,$per_page";
            $result = mysqli_query($link, $query);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = $row['id'];
                $name = $row['name_train'];
              ?>
                        <tr class="row<?=$i; ?>">
                            <td class="com"><?php echo $i; ?></td>
                            <td class="com"><?php echo $name; ?></td>
                            
                            <td class="com">
                                <button class="edit" type="submit" name="<?=$name; ?>" value="<?=$id; ?>" id="<?=$i; ?>">
                                Редактировать</button>
                                
                                <button type="submit" class="delete" value="<?=$id;?>" id="<?=$i; ?>">
                                Удалить</button>
                            </td>
                         
                        </tr>

                        <?php
                        $i++;
            }

                ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th><button class="add" type="submit"  value="" id="<?=$i; ?>">
                                Добавить поезд</button></th>
               
                </tr>
                </tbody>
            </table>
            <?php

    $query = "SELECT * FROM `trains_list`";
    $result = mysqli_query($link,$query);
    $total_rows = mysqli_num_rows($result);
    $num_pages = ceil($total_rows/$per_page);
    for($i=1;$i<=$num_pages;$i++) {
       if ($i-1 == $page) {
        echo '<a class="page_non" href="">'.$i."</a> ";
      } else {
        echo '<a class="page" href="'.$_SERVER['PHP_SELF'].'?train_list=true&page='.$i.'">'.$i."</a> ";
      }
    }

        }   // Список сообщений
        ?>




    
</div>

</body>
</html>


<?php

mysqli_close($link);

?>