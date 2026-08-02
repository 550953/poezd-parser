<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once '../../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

?>
<html>
<head>
    <title>Админка</title>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="../style.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

</head>
<body>
    <table style="width: 50%;">
        <tbody>

        <tr>
            <th><a href=../index.php>Назад</a></th>
            <th>Информация о пользователях</th>
        </tr>
        <?php

		$count = 0;
        $query = "SELECT * FROM `users`";
        $result = mysqli_query($link, $query);
        $count = mysqli_num_rows($result);

        
        ?>
        <tr>
            <th>Количество пользователей</th>
            <th><?php echo $count; ?></th>
        </tr>
       
        <tr>
            <th>Добавить пользователям:</th>
            <th>
<!--                <form method="post" action="add_time_all_user.php">-->
     
                    <input type="text" name="day" size="10"> дней
                    <button class="but" type="submit" name="id" value="">Добавить</button>

<!--                </form>-->
            </th>
        </tr>


        </tbody>
    </table>
    <script type="text/javascript">
        $('.but').click(function() {

            var day = $('input[name="day"]').val();
            $.ajax({
                type: "POST",
                url: "add_time_all_user.php",
                data: { 
                        day: day
                         },
                success: function(response) {
                    alert("Выполнено!");
                	console.log(response);
                  //  location.reload();
                }
                
            });
        });
    </script>
</body>
</html>


<?php

mysqli_close($link);

?>