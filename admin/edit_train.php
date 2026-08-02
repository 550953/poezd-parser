<?php
require_once '../api/connection.php'; // подключаем скрипт
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

mysqli_set_charset($link, "utf8");


if (isset($_POST['id_edit'])) {
    
     echo get_part($_POST['id_edit'], $_POST['index'], $_POST['name'], $link);
}


function get_part($id, $index, $name, $link){
    
    $query = "SELECT * FROM `trains_list` WHERE `id`='".$id."'";
    $name = "";
    $result = mysqli_query($link, $query);
    if($result) {
       $row = mysqli_fetch_assoc($result);
            $name = $row['name_train'];
     
    }
    
    $result_info = "<form id=\"form\" enctype=\"multipart/form-data\">
    <table><tbody>
    <tr>
    <th>Название</th>
    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"name\" value=\"".$name."\" size=\"80\"></td>

    </tr>
    <tr>
    
    
                                
    <td class=\"com\">
    
    <button class=\"close\" type=\"submit\">
                                Отменить</button>
    <button class=\"save\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}


if (isset($_POST['id_save'])) {

    echo save_part($_POST['id_save'], $_POST['name'], $_POST['index'], $link);

}


function save_part($id, $name, $index, $link){
    
 
    $query = "UPDATE `trains_list` SET `name_train` = '".$name."' WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    
    if($result) {

    $result_info = "<td class=\"com\">".$index."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Редактировать</button>
                                 <button type=\"submit\" class=\"delete\" value=\"".$id."\" id=\"".$index."\">
                                Удалить</button>

                    </td>";
    }
    
     
    mysqli_close($link);
    return $result_info;
    
}

if (isset($_POST['id_delete'])) {
    echo delete_part($_POST['id_delete'], $_POST['index'], $link);
}
function delete_part($id, $index, $link){
    $query = "DELETE FROM `trains_list` WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    if($result){
        $result_info = "Ok";
    }else{
        $result_info = "Error";
    }
    
    mysqli_close($link);
    return $result_info;
}


if (isset($_POST['index_add'])) {

     echo add_part($_POST['index_add'], $link);
}


function add_part($index, $link){

    $result_info = "<form id=\"form\" enctype=\"multipart/form-data\">
    <table><tbody>
    <tr>
    <th>Название</th>

    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"name\" value=\"\" size=\"80\"></td>

    </tr>
    <tr>
    
    
                                
    <td class=\"com\">
    
    <button class=\"close\" type=\"submit\">
                                Отменить</button>
    <button class=\"save_add\" type=\"submit\" name=\"\" value=\"\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}


if (isset($_POST['index_save'])) {
    
    echo save_part_new($_POST['name'] , $_POST['index_save'], $link);

}


function save_part_new($name, $index, $link){
    $query = "INSERT INTO `trains_list` (`id`, `name_train`) VALUES (NULL, '".$name."')";
    $result = mysqli_query($link, $query);
    $id = mysqli_insert_id($link);

    if($result) {
        
     $result_info = "<tr class=\"row".$index."\"><td class=\"com\">".$index."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Редактировать</button>
                                 <button type=\"submit\" class=\"delete\" value=\"".$id."\" id=\"".$index."\">
                                Удалить</button>

                    </td></tr>";
    }
    
     
    mysqli_close($link);
    return $result_info;
}


?>