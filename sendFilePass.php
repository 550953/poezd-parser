<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../php_mailer/Exception.php';
require '../php_mailer/PHPMailer.php';
require '../php_mailer/SMTP.php';



$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$subject = "Данные пассажиров:\n\n";
$title = "Проводник вагона";
//$messege .= $data['message'];

$message = $_POST['message'];
$emailUser = $_POST['mail'];
$stamp = $_POST['stamp'];
$emailSender = "provodnik@provodnik.xyz"; // от кого


if($_POST['mail'] != null){
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/passInfo/';
   // print_r($_FILES);
	$name = $_POST['mail'];
	$uploadfile = $uploaddir . basename($_FILES['file']['name']);
 // if ( !file_exists($uploaddir) ) {
 //     mkdir ($dir, 0744);
 // }

	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    	//mail_attachment($uploadfile, $messege, $mail, $email, "Проводник вагона", $email, $messege, $messege);
    	//
    	$mail = new PHPMailer;
		$mail->setFrom($emailSender, $title);
		$mail->addAddress($emailUser,$emailUser);
		$mail->Subject  = $subject;
		$mail->Body     = $message;
		$mail->addStringAttachment(file_get_contents($uploadfile), $stamp.'pv.json');
		if(!$mail->send()) {
  			echo 'Message was not sent.';
  			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
  			echo 'Message has been sent.';
		}

    	echo "Файл корректен и был успешно загружен.\n";
	} else {
    	echo "Возможная атака с помощью файловой загрузки!\n";
	}
}




?>