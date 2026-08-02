<?php
require_once '../api/connection.php'; // подключаем скрипт

$link = mysqli_connect($host_book, $user_book, $password_book, $database_book) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

if(isset($_POST['id_delete'])){
	$id = $_POST['id_delete'];
	deleteBook($link, $id);
}

function deleteBook($link, $id){
	$query = "DELETE FROM `previe_book` WHERE `id` = ".$id;
	$result = mysqli_query($link, $query);
}

if (isset($_POST['index_add'])) {

     echo add_part($_POST['index_add'], $link);
}

if (isset($_POST['id_delete'])) {
    echo delete_part($_POST['id_delete'], $_POST['index'], $link);
}
function delete_part($id, $index, $link){
    $query = "DELETE FROM `previe_book` WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    if($result){
        $result_info = "Ok";
    }else{
        $result_info = "Error";
    }
    
    mysqli_close($link);
    return $result_info;
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
    <th>Идентификатор</th>

    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"uid\" value=\"\" size=\"80\"></td>

    </tr>
    <tr>
                         
    <td class=\"com\">
    
    <button class=\"close\" type=\"submit\">
                                Отменить</button>
    <button class=\"save_add_book\" type=\"submit\" name=\"\" value=\"\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}

if (isset($_POST['index_save'])) {
    
    echo save_part_new($_POST['name'], $_POST['book_id'], $_POST['index_save'], $link);

}


function save_part_new($name, $book_id, $index, $link){
    $query = "INSERT INTO `previe_book` (`id`, `name`, `id_book`) VALUES (NULL, '".$name."', '".$book_id."')";
    $result = mysqli_query($link, $query);
    $id = mysqli_insert_id($link);

    if($result) {
        
     $result_info = "<tr class=\"row_book".$index."\"><td class=\"com\">".$index."</td>
                    <td class=\"com\">".$book_id."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit_book\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id_book=\"".$book_id."\" id=\"".$index."\">
                                Редактировать книгу</button>
                                 <button type=\"submit\" class=\"delete_book\" value=\"".$id."\" id=\"".$index."\">
                                Удалить книгу</button>

                    </td></tr>";
    }
    
     
    mysqli_close($link);
    return $result_info;
}


if (isset($_POST['id_edit'])) {
    
     echo get_part($_POST['id_edit'], $_POST['index'], $link);
}


function get_part($id, $index, $link){
    
    $query = "SELECT * FROM `previe_book` WHERE `id`='".$id."'";
    $name = "";
    $id_book = "";
    $result = mysqli_query($link, $query);
    if($result) {
       $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
    		$id_book = $row['id_book'];
     
    }
    
    $result_info = "<form id=\"form\" enctype=\"multipart/form-data\">
    <table><tbody>
    <tr>
    <th>Название</th>
    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"name\" value=\"".$name."\" size=\"80\"></td>

    </tr>
    <tr>
    <tr>
    <th>Идентификатор</th>

    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"uid\" value=\"".$id_book."\" size=\"80\"></td>

    </tr>
    <tr>
    
                                
    <td class=\"com\">
    
    <button class=\"close\" type=\"submit\">
                                Отменить</button>
    <button class=\"save_book\" type=\"submit\" name=\"".$name."\" book_id=\"".$id_book."\" value=\"".$id."\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}

if (isset($_POST['id_save'])) {

    echo save_part($_POST['id_save'], $_POST['name'], $_POST['book_id'], $_POST['index'], $link);

}


function save_part($id, $name, $book_id, $index, $link){
    
 
    $query = "UPDATE `previe_book` SET `name` = '".$name."', `id_book` = '".$book_id."' WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    
    if($result) {

    $result_info = "
    				<td class=\"com\">".$index."</td>
                    <td class=\"com\">".$book_id."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit_book\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id_book=\"".$book_id."\" id=\"".$index."\">
                                Редактировать книгу</button>
                                 <button type=\"submit\" class=\"delete_book\" value=\"".$id."\" id=\"".$index."\">
                                Удалить книгу</button>

                    </td>";
    }
    
     
    mysqli_close($link);
    return $result_info;
    
}


if (isset($_POST['id_edit_url'])) {
    if($_POST['id_edit_url'] != 1 )
     	echo get_part_url($_POST['id_edit_url'], $_POST['index'], $link);
    else
       echo get_part_url_app($_POST['id_edit_url'], $_POST['index'],$_POST['url_app'], $link);
}


function get_part_url($id, $index, $link){
    
    $query = "SELECT * FROM `url_parser` WHERE `id`='".$id."'";
    $name = "";
    $result = mysqli_query($link, $query);
    if($result) {
       $row = mysqli_fetch_assoc($result);
            $name = $row['url'];
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
    <button class=\"save_url\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}

function get_part_url_app($id, $index, $url_app, $link){
    
    $query = "SELECT * FROM `url_parser` WHERE `id`='".$id."'";
    $name = "";
    $result = mysqli_query($link, $query);
    if($result) {
       $row = mysqli_fetch_assoc($result);
            $name = $row['url'];
            $url_app = $row['url_app'];
    }
    
    $result_info = "<form id=\"form\" enctype=\"multipart/form-data\">
    <table><tbody>
    <tr>
    <th>Название</th>
    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"name\" value=\"".$name."\" size=\"80\"></td>

    </tr>
    
    <tr>
    
     <tr>
    <th>Ссылка на апк</th>
    </tr>
    
    <tr><td class=\"com\"><input type=\"text\" class=\"url_app\" value=\"".$url_app."\" size=\"80\"></td>

    </tr>
    
    <tr>
                          
    <td class=\"com\">
    
    <button class=\"close\" type=\"submit\">
                                Отменить</button>
    <button class=\"save_url\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}


if (isset($_POST['id_save_url'])) {

	if($_POST['id_save_url'] != 1)
    	echo save_part_url($_POST['id_save_url'], $_POST['name'], $_POST['index'], $link);
	else
    	echo save_part_url_app($_POST['id_save_url'], $_POST['name'], $_POST['url_app'], $_POST['index'], $link);
}


function save_part_url($id, $name,  $index, $link){
    
 
    $query = "UPDATE `url_parser` SET `url` = '".$name."' WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    
    if($result) {

    $result_info = "
    				<td class=\"com\">".$index."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit_url\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Редактировать адрес</button>

                    </td>";
    }
    
     
    mysqli_close($link);
    return $result_info;
    
}
function save_part_url_app($id, $name, $url_app, $index, $link){
    
	
    $query = "UPDATE `url_parser` SET `url` = '".$name."', `url_app` = '".$url_app."' WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    
    if($result) {

    $result_info = "
    				<td class=\"com\">".$index."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">".$url_app."</td>
                    <td class=\"com\">
                        <button class=\"edit_url\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\" app=\"".$url_app."\" >
                                Редактировать</button>

                    </td>";
    }
    
     
    mysqli_close($link);
    return $result_info;
    
}


if (isset($_POST['id_edit_ver'])) {
    
     echo get_part_ver($_POST['id_edit_ver'], $_POST['index'], $link);
}


function get_part_ver($id, $index, $link){
    
    $query = "SELECT * FROM `url_parser` WHERE `id`='".$id."'";
    $name = "";
    $result = mysqli_query($link, $query);
    if($result) {
       $row = mysqli_fetch_assoc($result);
            $name = $row['url'];
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
    <button class=\"save_ver\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Сохранить</button>
    </td>
    
    </tr>
    
    </tbody></table></form>";
     
    mysqli_close($link);
    return $result_info;
    
}

if (isset($_POST['id_save_ver'])) {

    echo save_part_ver($_POST['id_save_ver'], $_POST['name'], $_POST['index'], $link);

}


function save_part_ver($id, $name,  $index, $link){
    
 
    $query = "UPDATE `url_parser` SET `url` = '".$name."' WHERE `id` = ".$id;
    $result = mysqli_query($link, $query);
    
    if($result) {

    $result_info = "
    				<td class=\"com\">".$index."</td>
                    <td class=\"com\">".$name."</td>
                    <td class=\"com\">
                        <button class=\"edit_ver\" type=\"submit\" name=\"".$name."\" value=\"".$id."\" id=\"".$index."\">
                                Редактировать</button>

                    </td>";
    }
    
     
    mysqli_close($link);
    return $result_info;
    
}








mysqli_close($link);
?>