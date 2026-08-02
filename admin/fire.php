


<!--<script src="https://www.gstatic.com/firebasejs/4.11.0/firebase.js"></script>-->
<!--<script src="https://www.gstatic.com/firebasejs/4.10.1/firebase-database.js"></script>-->
<!--<script>-->
<!--    // Initialize Firebase-->
<!--    var config = {-->
<!--        apiKey: "AIzaSyCm51S15bcPZNW6WMFek_vohneVlXmU9C4",-->
<!--        authDomain: "train-9b5c9.firebaseapp.com",-->
<!--        databaseURL: "https://train-9b5c9.firebaseio.com",-->
<!--        projectId: "train-9b5c9",-->
<!--        storageBucket: "train-9b5c9.appspot.com",-->
<!--        messagingSenderId: "971682035015"-->
<!--    };-->
<!--    firebase.initializeApp(config);-->
<!--    console.log(defaultApp.name);  // "[DEFAULT]"-->
<!---->
<!--    // You can retrieve services via the defaultApp variable...-->
<!--    var defaultStorage = defaultApp.storage();-->
<!--    var defaultDatabase = defaultApp.database();-->
<!---->
<!--</script>-->

<?php
/**
 * Created by PhpStorm.
 * User: anatolijmakarenko
 * Date: 17.03.2018
 * Time: 18:12
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
//require './firebase/src/firebaseLib.php';
//////$url = 'https://train-9b5c9.firebaseio.com';
//////$token = 'AIzaSyCm51S15bcPZNW6WMFek_vohneVlXmU9C4';
//////$firebase = new Firebase($url, $token);
//////print_r($firebase);
//////$firebase->get('something/from/somewhere');
////
const DEFAULT_URL = 'https://train-9b5c9.firebaseio.com/';
const DEFAULT_TOKEN = 'AIzaSyCm51S15bcPZNW6WMFek_vohneVlXmU9C4';
const DEFAULT_PATH = 'messages';
//
//$firebase = new Firebase(DEFAULT_URL, DEFAULT_TOKEN);
//
//// --- storing an array ---
//$test = array(
//    "foo" => "bar",
//    "i_love" => "lamp",
//    "id" => 42
//);
//
//$name = $firebase->get(DEFAULT_PATH . '/-L7nTuZxQrv8LOyh24_T/-L7nyA893Uooek007Zsk/message');
//echo $name;
//$dateTime = new DateTime();
////$firebase->set(DEFAULT_PATH . '/' . $dateTime->format('c'), $test);
////
////// --- storing a string ---
////$firebase->set(DEFAULT_PATH . '/name/contact001', "John Doe");
////
////// --- reading the stored string ---
////$name = $firebase->get(DEFAULT_PATH . '/name/contact001');
$curl = curl_init();
$chat = curl_setopt($curl,CURLOPT_URL, DEFAULT_URL.DEFAULT_PATH.".json");
//curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
$response = curl_exec( $curl );
curl_close( $curl );
// Show result
//echo $response . "\n";
echo "<pre>";
echo (json_decode($response, true));
echo "<pre>";