<?php 
// if(isset($_POST['phone'])){
//     // $to = 'alkomigom96.ru@yandex.ru,Alkomig9696@yandex.ru'; // this is your Email address
//     $to = 'web@nfresh.ru'; // this is your Email address
//     $from = 'webmaster@ekb-alkogol.ru'; // this is the sender's Email address
//     $product = $_POST['product'];
//     $name = $_POST['name'];
//     $phone = $_POST['phone'];
//     $subject = "Заявка с сайта";
//     $message = 'Товар: ' . $product . '<br> Имя: ' . $name . '<br> Телефон: ' . $phone;

//     // To send HTML mail, the Content-type header must be set
//     $headers[] = 'MIME-Version: 1.0';
//     $headers[] = 'Content-type: text/html; charset=utf-8';

//     // Additional headers
//     $headers[] = 'To: ' . $to;
//     $headers[] = 'From: ' . $from;
//     mail($to, $subject, $message, $headers);
//     echo "Письмо отправлено.";
//     // echo implode("\r\n", $headers); //try without implode
//     // You can also use header('Location: thank_you.php'); to redirect to another page.
// }

// header("Location: /thankyou/");
// die();


// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Переменные, которые отправляет пользователь
$product = $_POST['product'];
$name = $_POST['name'];
$phone = $_POST['phone'];

if (!$product) {
    $product = 'Форма в подвале';
}

// Формирование самого письма
$title = "Заявка с сайта";
$body = 'Товар: ' . $product . '<br> Имя: ' . $name . '<br> Телефон: ' . $phone;

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try { 
    $mail->CharSet = "UTF-8";

    // Отправитель письма
    $mail->setFrom('noreply@ekb-alkogol.ru', 'Сайт ekb-alkogol.ru'); // ТУТ НАДО МЕНЯТЬ ДОМЕНЫ С ПЕРЕЕЗДАМИ САЙТОВ

    // Получатель письма
    $mail->addAddress('alkomigom96.ru@yandex.ru,Alkomig9696@yandex.ru'); // адреса алкомига
    // $mail->addAddress('web@nfresh.ru'); // тест

    // Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;    

    // Проверяем отравленность сообщения
    if ($mail->send()) {$result = "success";} 
    else {$result = "error";}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

// Отображение результата
// echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);
header("Location: /thankyou/");
die();
?>