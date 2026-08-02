<?php
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
//
//$trains = $_GET['trains'];
//$full_name = $_GET['full_name'];


$delete = $data['uid_delete'];
if($delete != null){

	$query = "DELETE FROM `tea_object` WHERE `tea_object`.`id` = '".$delete."'";
    $result = mysqli_query($link, $query);
         if($result){
             $all_mass = array(
                 'messege' => 'true'
             );
         }
		echo json_encode($all_mass, true);

}
$uid = $data['uid'];
 if($uid != null){
     if(count(getTeaObject($link)) == 0){
         $all_mass = array(  // Формируем массив
             'messege' => 'false'
         );
     }else{
         $all_mass = getTeaObject($link);
     }
     echo json_encode($all_mass, true);

 }
$id = $data['id'];
if($id == null)
	$id=0;
$name = $data['name'];
$category = $data['category'];
$url_image = $data['url_image'];
$sr_price = $data['sr_price'];
$code = $data['code'];
 if($name != null && $category != null){
     $result = addTeaObject($id, $name, $category, $url_image, $sr_price, $code, $link);
     echo json_encode($result, true);
 }


 function addTeaObject($id, $name, $category, $url_image, $sr_price, $code, $link){

     $query = "SELECT * FROM `tea_object` WHERE `id`='" . $id . "'";

     $result = mysqli_query($link, $query);
     $count = mysqli_num_rows($result);


     if ($count > 0) {
         $query = "UPDATE `tea_object` SET `name`='".$name."',`category`='".$category."',`url_image`='".$url_image."',`sr_price`='".$sr_price."',`code`='".$code."' WHERE `id`='" . $id . "'";
         //$result = mysqli_query($link, $query);
     	$result = true;
         if($result){
             $all_mass = array(
                 'messege' => 'true'
             );
         }

     }else{
         $query = "INSERT INTO `tea_object`(`id`, `name`, `category`, `url_image`, `sr_price`, `code`) VALUES (null,'".$name."','".$category."','".$url_image."','".$sr_price."','".$code."')";
         $result = mysqli_query($link, $query);
         if($result){
             $all_mass = array(
                 'messege' => 'true'
             );
         }}


     return $all_mass;
 }


 function getTeaObject($link){
     $result_date = array();
     $query = "SELECT * FROM `tea_object` ORDER BY `tea_object`.`id` DESC";
     $result = mysqli_query($link, $query);
     if ($result) {
         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

             $result_date[] = array(
             	"id" => $row['id'],
                 "name" => $row['name'],
                 "category" => $row['category'],
                 "url_image" => $row['url_image'],
                 "sr_price" => $row['sr_price'],
                 "code" => $row['code']
             );
         }

     }
     return $result_date;
 }

// закрываем подключение
mysqli_close($link);
?>