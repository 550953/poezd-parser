<?php
header('Content-type: application/json');
// error_reporting(E_ALL);
// ini_set("display_errors", 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../php_mailer/Exception.php';
require '../php_mailer/PHPMailer.php';
require '../php_mailer/SMTP.php';





// $mail = new PHPMailer(true);

// try {
//     //Server settings
//     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
//     $mail->isSMTP();                                            // Send using SMTP
//     $mail->Host       = 'smtp1.example.com';                    // Set the SMTP server to send through
//     $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//     $mail->Username   = 'user@example.com';                     // SMTP username
//     $mail->Password   = 'secret';                               // SMTP password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
//     $mail->Port       = 587;                                    // TCP port to connect to

//     //Recipients
//     $mail->setFrom('from@example.com', 'Mailer');
//     $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
//     $mail->addAddress('ellen@example.com');               // Name is optional
//     $mail->addReplyTo('info@example.com', 'Information');
//     $mail->addCC('cc@example.com');
//     $mail->addBCC('bcc@example.com');

//     // Attachments
//     $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//     $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

//     // Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = 'Here is the subject';
//     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$subject = "Бланк ЛУ-72:\n\n";
$title = "Проводник вагона";
//$messege .= $data['message'];

$message = $_POST['message'];
$emailUser = $_POST['mail'];
$emailSender = "provodnik@provodnik.xyz"; // ?? ????


if($_POST['mail'] != null){
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/api/poezd/parse/historylu72/';
   // print_r($_FILES);
	$name = $_POST['mail'];
	$uploadfile = $uploaddir . basename($_FILES['file']['name']);
 // if ( !file_exists($uploaddir) ) {
 //     mkdir ($dir, 0744);
 // }

	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    	//mail_attachment($uploadfile, $messege, $mail, $email, "????????? ??????", $email, $messege, $messege);
    	//
    	$mail = new PHPMailer;
    	$mail->CharSet = 'UTF-8';
    	$mail->Encoding = 'base64';
		$mail->setFrom($emailSender, $title);
		$mail->addAddress($emailUser,$emailUser);
		$mail->Subject  = $subject;
		$mail->Body     = $message;
		$mail->addStringAttachment(file_get_contents($uploadfile), 'document.html');
		if(!$mail->send()) {
  			// echo 'Message was not sent.';
  			// echo 'Mailer error: ' . $mail->ErrorInfo;
            echo json_encode( array('messege' => 'false'));
		} else {
  			//echo 'Message has been sent.';
         	echo json_encode( array('messege' => 'Message has been sent'));
		}
 		//echo json_encode( array('messege' => "Файл корректен и был успешно загружен.\n"));
    	//echo "???? ????????? ? ??? ??????? ????????.\n";
	}
}




?>