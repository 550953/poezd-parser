
<!--<script src="https://www.gstatic.com/firebasejs/4.11.0/firebase.js"></script>-->
<!--<script>-->

<!--  var config = {-->
<!--    apiKey: "AIzaSyCm51S15bcPZNW6WMFek_vohneVlXmU9C4",-->
<!--    authDomain: "train-9b5c9.firebaseapp.com",-->
<!--    databaseURL: "https://train-9b5c9.firebaseio.com",-->
<!--    projectId: "train-9b5c9",-->
<!--    storageBucket: "train-9b5c9.appspot.com",-->
<!--    messagingSenderId: "971682035015"-->
<!--  };-->
<!--  firebase.initializeApp(config);-->
<!--</script>-->
<?php
require "vendor/autoload.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);
const DEFAULT_URL = 'https://train-9b5c9.firebaseio.com/';
const DEFAULT_TOKEN = 'fm1GhKwuXophcjJW2kzvxtiDw8JHkJ525FTKvdHT';
const DEFAULT_PATH = '/messages';

$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);
$name = $firebase->get(DEFAULT_PATH);
$path = DEFAULT_PATH . '/-L7nTuZxQrv8LOyh24_T';
// $value = [
//         'title' => 'Post title',
//         'body' => 'This should probably be longer.'
//     ];

$getListFire = $firebase->get($path);
$decodeListFire = json_decode($getListFire);
echo $getListFire;
if($decodeListFire != null){
    echo "hffff";
 foreach ($decodeListFire as $s){
    // print_r($s['message']) ;
     echo $s->message;
 }
}
//$value = $firebase->set($path,$value);
//echo ($value);
?>