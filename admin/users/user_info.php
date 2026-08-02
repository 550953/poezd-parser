<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once '../../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
$id = $_GET['id'];
?>
<html>
<head>
    <title>Админка</title>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="../style.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>
<body>
    <table style="width: 100%;">
        <tbody>

        <tr>
            <th><a class="back" href=../index.php?user=true>Назад</a></th>
            <th>Информация о пользователе</th>
        </tr>
        <?php


        $query = "SELECT * FROM `users` WHERE `id`=".$id;
        $result = mysqli_query($link, $query);

        $i = 1;
        if($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_user = $row['id'];
                $uid = $row['uid'];
                $name = $row['name'];
                $email = $row['email'];
                $date_create = $row['date_create'];
            }

            $find = "SELECT * FROM `subscription` WHERE `id_user`=" . $id_user;
            $result_find = mysqli_query($link, $find);

            if ($result_find) {
                while ($row_find = mysqli_fetch_array($result_find, MYSQLI_ASSOC)) {

                    $id_sub = $row_find['id'];
                    $id_sub_s = $row_find['date_start'];
                    $id_sub_e = $row_find['date_end'];

                }
            }
        }

        ?>
        <tr>
            <td class="com info">Имя:</td>
            <td class="com info"><?php echo $name; ?></td>
        </tr>
        <tr>
            <td class="com info">Uid:</td>
            <td class="com info"><?php echo $uid; ?></td>
        </tr>
        <tr>
            <td class="com info">Email:</td>
            <td class="com info"><?php echo $email; ?></td>
        </tr>
        <tr>
            <td class="com info">Дата регистрации:</td>
            <td class="com info"><?php echo date("H:i:s -- d.m.Y", $date_create/1000); ?></td>
        </tr>
        <tr>
            <td class="com info">Дата начала подписки:</td>
            <td class="com info"><?php echo date("H:i:s -- d.m.Y", $id_sub_s/1000); ?></td>
        </tr>
        <tr>
            <td class="com info">Дата окончания подписки:</td>
            <td class="com info"><?php echo date("H:i:s -- d.m.Y", $id_sub_e/1000); ?></td>
        </tr>
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr class="info_tr">
            <th>Добавить пользователю:</th>
            <th>
<!--                <form method="post" action="add_day_user.php">-->
                    <input type="hidden" name="id_user" value="<?=$id; ?>">
                    <input type="hidden" name="date_end" value="<?=$id_sub_e; ?>">
                    <input class="searchDay" type="text" name="day" size="10"> дней
                    <button class="but" type="submit" name="id" value="<?=$id_sub; ?>">Добавить</button>

<!--                </form>-->
            </th>
        </tr>
            
              <tr>
            <th>Добавить пользователю:</th>
            <th>

                    <input type="hidden" name="id_user" value="<?=$id; ?>">
                    <input type="hidden" name="date_end" value="<?=$id_sub_e; ?>">
                 
                    <button class="but100" type="submit" name="id" value="<?=$id_sub; ?>">Добавить 90 дней</button>


            </th>
        </tr>


        </tbody>
    </table>
    <script type="text/javascript">
        $('.but').click(function() {

            var day = $('input[name="day"]').val();
            $.ajax({
                type: "POST",
                url: "add_day_user.php",
                data: { id: <?=$id_sub; ?>,
                        day: day,
                        date_end: <?=$id_sub_e; ?> },
                success: function() {
                    alert("Выполнено!");
                    location.reload();
                }
            });
        });
    </script>
    
     <script type="text/javascript">
        $('.but100').click(function() {

            var day = 90;
            $.ajax({
                type: "POST",
                url: "add_day_user.php",
                data: { id: <?=$id_sub; ?>,
                        day: day,
                        date_end: <?=$id_sub_e; ?> },
                success: function() {
                    alert("Выполнено!");
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>


<?php

mysqli_close($link);

?>