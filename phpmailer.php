<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$mail = new PHPMailer(true);

try {
    // Настройки SMTP SpaceWeb
    $mail->isSMTP();
    $mail->Host = 'smtp.spaceweb.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@madeinkhakassia.ru'; // Полный email
    $mail->Password = '51wGa#Rh';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    // От кого
    $mail->setFrom('mail@madeinkhakassia.ru', 'Сайт "Сделано в Хакасии"');
    
    // Кому
    $mail->addAddress('fondrh@mail.ru');
    
    // Тема и тело
    $mail->Subject = 'Новая заявка';
    $mail->Body    = json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка отправки',
        'debug' => $mail->ErrorInfo // Только для разработки!
    ]);
}
?>
