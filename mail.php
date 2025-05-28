<?php
header("Access-Control-Allow-Origin: https://www.madeinkhakassia.ru");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Предварительный CORS-запрос
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Проверяем метод
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(["error" => "Только POST-запросы разрешены"]));
}

// Данные из формы
$data = json_decode(file_get_contents('php://input'), true);
$name = htmlspecialchars($data['name'] ?? '');
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($data['message'] ?? '');

// Настройки SMTP (для mail@madeinkhakassia.ru)
require 'vendor/autoload.php'; // Подключение PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.beget.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@madeinkhakassia.ru';
    $mail->Password = 'ваш_пароль';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('mail@madeinkhakassia.ru', 'Заявка с сайта');
    $mail->addAddress('fondrh@mail.ru');
    $mail->Subject = "Новая заявка от $name";
    $mail->Body = "Имя: $name\nEmail: $email\nСообщение: $message";

    $mail->send();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Ошибка отправки: " . $e->getMessage()]);
}
