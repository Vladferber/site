<?php
header('Content-Type: application/json');

// Проверка, что запрос отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

// Получение данных из формы
$data = json_decode(file_get_contents('php://input'), true);

// Валидация данных
$errors = [];
if (empty($data['name'])) {
    $errors[] = 'Не указано имя';
}
if (empty($data['phone']) && empty($data['email'])) {
    $errors[] = 'Не указаны контактные данные (телефон или email)';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Подготовка данных
$name = htmlspecialchars(trim($data['name']));
$phone = !empty($data['phone']) ? htmlspecialchars(trim($data['phone'])) : 'Не указан';
$email = !empty($data['email']) ? htmlspecialchars(trim($data['email'])) : 'Не указан';
$message = !empty($data['message']) ? htmlspecialchars(trim($data['message'])) : 'Не указано';

// Тема письма
$subject = 'Новая заявка с сайта madeinkhakassia.ru';

// Тело письма
$body = "
<html>
<head>
    <title>{$subject}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f8f8; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .field { margin-bottom: 15px; }
        .field-name { font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>{$subject}</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='field-name'>Имя:</div>
                <div>{$name}</div>
            </div>
            <div class='field'>
                <div class='field-name'>Телефон:</div>
                <div>{$phone}</div>
            </div>
            <div class='field'>
                <div class='field-name'>Email:</div>
                <div>{$email}</div>
            </div>
            <div class='field'>
                <div class='field-name'>Сообщение:</div>
                <div>{$message}</div>
            </div>
        </div>
    </div>
</body>
</html>
";

// Настройки PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Настройки сервера
    $mail->isSMTP();
    $mail->Host = 'smtp.spaceweb.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@madeinkhakassia.ru'; // Ваш email на Space Web
    $mail->Password = '51wGa#Rh'; // Пароль от почты
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Используйте ENCRYPTION_STARTTLS для порта 587
    $mail->Port = 465; // 587 для TLS

    // Отправитель и получатель
    $mail->setFrom('mail@madeinkhakassia.ru', 'Made in Khakassia');
    $mail->addAddress('fondrh@mail.ru', 'Получатель');
    
    // Дополнительные получатели (если нужно)
    // $mail->addCC('another@email.com');
    
    // Контент письма
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = strip_tags($body); // Текстовая версия письма

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Заявка успешно отправлена']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Ошибка при отправке заявки: {$mail->ErrorInfo}"]);
}
