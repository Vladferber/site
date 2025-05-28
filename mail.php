<?php
header("Access-Control-Allow-Origin: https://www.madeinkhakassia.ru");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Обработка предварительного CORS-запроса
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Метод не разрешен']));
}

// Получение и валидация данных
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['error' => 'Неверный формат данных']));
}

$required = ['name', 'email', 'message'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        die(json_encode(['error' => "Поле $field обязательно"]));
    }
}

// Очистка данных
$name = htmlspecialchars(trim($data['name']));
$email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($data['message']));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['error' => 'Неверный email']));
}

// Настройки PHPMailer
require 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Настройки SMTP для mail@madeinkhakassia.ru
    $mail->isSMTP();
    $mail->Host = 'smtp.spaceweb.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@madeinkhakassia.ru';
    $mail->Password = '51wGa#Rh'; // Замените на реальный пароль
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 25;
    $mail->CharSet = 'UTF-8';

    // От кого
    $mail->setFrom('mail@madeinkhakassia.ru', 'Сайт madeinkhakassia.ru');
    
    // Кому
    $mail->addAddress('fondrh@mail.ru');
    
    // Тема и тело письма
    $mail->Subject = "Новая заявка от $name";
    $mail->Body = "Имя: $name\nEmail: $email\n\nСообщение:\n$message";
    $mail->AltBody = strip_tags($message);

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка отправки: ' . $e->getMessage()]);
}
