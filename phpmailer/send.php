<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

$data = json_decode(file_get_contents('php://input'), true);

$formname = $data['form']['name'];
if (!$formname) {
    $formname = 'с сайта vsesvoi-a.ru';
}

// $title = "Заполнена форма $formname";
$title = "Качин- Мск- Импланты (1+1 AnyOne)";

$body = '';
$fields = $data['fields'];
$fields_arr = [];
foreach ($fields as $field) {
    $fields_arr[] = $field['name'] . ': ' . $field['value'];
}
if (count($fields_arr)) {
    $body = implode('<br> ', $fields_arr);
}

// Настройки PHPMailer
$mail = new PHPMailer();
try { 
    $mail->CharSet = "UTF-8";
    
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.beget.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Username = 'noreply@vsesvoi-a.ru';
    $mail->Password = '1mp%a3bv%n8P';

    // Отправитель письма
    $mail->setFrom('noreply@vsesvoi-a.ru', 'Сайт vsesvoi-a.ru');

    // Получатель письма
    $mail->addAddress('leadcollector@wilstream.ru');

    // Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;

    // Проверяем отравленность сообщения
    if ($mail->send()) {
        $result = 1;
        $errors = [];
    } else {
        $result = 0;
        $errors = ["Произошла ошибка при отправке сообщения: $mail->ErrorInfo"];
    }

} catch (Exception $e) {
    $result = 0;
    $errors = ['Произошла ошибка при отправке сообщения.'];
}

$response = [
    'result' => $result,
    'errors' => $errors,
    'title' => $title,
    'body' => $body,
];
echo json_encode($response);