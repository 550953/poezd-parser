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

    <link rel="stylesheet" href="../style.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>
<body>
<table style="width: 50%;">
    <tbody>

    <tr>
        <th><a href=../index.php?message=true>Назад</a></th>
        <th>Информация о сообщении</th>
    </tr>
    <?php


    $query = "SELECT * FROM `email` WHERE `id`=".$id;
    $result = mysqli_query($link, $query);

    $i = 1;
    if($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
            $name = $row['name'];
            $email = $row['email'];
            $message = $row['message'];
            $vagon = $row['vagon'];
            $train = $row['train'];
            $date = $row['date'];
        }

    }

    ?>
    <tr>
        <td class="com">Имя:</td>
        <td class="com"><?php echo $name; ?></td>
    </tr>
    <tr>
        <td class="com">Email:</td>
        <td class="com"><?php echo $email; ?></td>
    </tr>
    <tr>
        <td class="com">Вагон:</td>
        <td class="com"><?php echo $vagon; ?></td>
    </tr>
    <tr>
        <td class="com">Поезд:</td>
        <td class="com"><?php echo $train; ?></td>
    </tr>
    <tr>
        <td class="com">Дата выезда:</td>
        <td class="com"><?php echo $date; ?></td>
    </tr>
    <tr>
        <td class="com">Сообщение:</td>
        <td class="com"><?php echo $message; ?></td>
    </tr>
    <tr>
        <th colspan="2" id="answer"></th>
    </tr>
        <tr>
            <th>Ответ пользователю:</th>
            <th>
                <textarea name="comment" cols="100" rows="10"></textarea>
                <input type="hidden" name="email" value="<?=$email; ?>">
            </th>
        </tr>
    <tr>
        <th></th>
        <th><button class="but" type="submit" name="id"?>Отправить</button></th>
    </tr>
    </tbody>
</table>
<script type="text/javascript">
    $('.but').click(function() {

        var message = $('textarea[name="comment"]').val();
        var email = $('input[name="email"]').val();
        $.ajax({
            type: "POST",
            url: "send_mail.php",
            data: {
                message: message,
                email: email },
            success: function() {
             //   $( "#answer" ).html(result);
                alert("Сообщение отправлено!");
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