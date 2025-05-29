<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Получаем данные из POST запроса
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Валидация
$errors = [];

if (empty($company)) {
    $errors[] = 'Название компании обязательно для заполнения';
}

if (empty($name)) {
    $errors[] = 'Контактное лицо обязательно для заполнения';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Введите корректный email';
}

if (empty($phone)) {
    $errors[] = 'Телефон обязателен для заполнения';
}

if (empty($subject)) {
    $errors[] = 'Тип продукции обязателен для заполнения';
}

if (empty($message)) {
    $errors[] = 'Описание продукции обязательно для заполнения';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Настройки для SpaceWeb
$from_email = 'mail@madeinkhakassia.ru';
$from_name = 'Сайт "Сделано в Хакасии"';
$to_email = 'fondrh@mail.ru';

// Формируем тему письма
$email_subject = 'Новая заявка на участие в программе "Сделано в Хакасии"';

// Формируем HTML письмо
$html_body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #f8f9fa; }
        .header { background: #2C5F85; color: white; padding: 30px 20px; text-align: center; }
        .content { padding: 30px 20px; background: white; }
        .field { margin-bottom: 20px; }
        .label { font-weight: bold; color: #2C5F85; margin-bottom: 5px; display: block; }
        .value { color: #333; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .footer { background: #2C5F85; color: white; padding: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Новая заявка</h1>
            <p>Программа \"Сделано в Хакасии\"</p>
        </div>
        <div class='content'>
            <div class='field'>
                <span class='label'>Название компании:</span>
                <div class='value'>" . htmlspecialchars($company) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Контактное лицо:</span>
                <div class='value'>" . htmlspecialchars($name) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Email:</span>
                <div class='value'>" . htmlspecialchars($email) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Телефон:</span>
                <div class='value'>" . htmlspecialchars($phone) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Тип продукции/услуг:</span>
                <div class='value'>" . htmlspecialchars($subject) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Описание продукции/услуг:</span>
                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Дата подачи заявки:</span>
                <div class='value'>" . date('d.m.Y H:i:s') . "</div>
            </div>
            <div class='field'>
                <span class='label'>IP адрес:</span>
                <div class='value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
            </div>
        </div>
        <div class='footer'>
            <p>Заявка отправлена с сайта \"Сделано в Хакасии\"</p>
            <p>Для ответа используйте email: " . htmlspecialchars($email) . "</p>
        </div>
    </div>
</body>
</html>";

// Заголовки письма
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Priority: 3\r\n";

// Отправляем письмо
if (mail($to_email, $email_subject, $html_body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Заявка успешно отправлена']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке заявки']);
}
?>
