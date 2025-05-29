<?php
header('Content-Type: application/json; charset=utf-8');

// Разрешаем CORS запросы
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Подключаем PHPMailer (если доступен) или используем встроенную функцию mail()
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Получаем данные из POST запроса
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Валидация
$errors = [];

if (empty($name)) {
    $errors[] = 'Имя обязательно для заполнения';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Введите корректный email';
}

if (empty($subject)) {
    $errors[] = 'Тема обязательна для заполнения';
}

if (empty($message)) {
    $errors[] = 'Сообщение обязательно для заполнения';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Настройки SMTP для SpaceWeb
$smtp_host = 'smtp.spaceweb.ru';
$smtp_port = 465;
$smtp_username = 'mail@madeinkhakassia.ru';
$smtp_password = '51wGa#Rh';
$from_email = 'mail@madeinkhakassia.ru';
$from_name = 'Сайт Made in Khakassia';
$to_email = 'fondrh@mail.ru';

// Проверяем, доступен ли PHPMailer
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    // Используем PHPMailer для более надежной отправки
    try {
        $mail = new PHPMailer(true);
        
        // Настройки SMTP
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $smtp_port;
        $mail->CharSet = 'UTF-8';
        
        // Отправитель и получатель
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to_email);
        $mail->addReplyTo($email, $name);
        
        // Содержимое письма
        $mail->isHTML(true);
        $mail->Subject = 'Новая заявка с сайта: ' . $subject;
        
        $html_body = "
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>
        <body>
            <h2>Новая заявка с сайта Made in Khakassia</h2>
            <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>
                <tr>
                    <td><strong>Имя:</strong></td>
                    <td>" . htmlspecialchars($name) . "</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>" . htmlspecialchars($email) . "</td>
                </tr>
                <tr>
                    <td><strong>Телефон:</strong></td>
                    <td>" . htmlspecialchars($phone) . "</td>
                </tr>
                <tr>
                    <td><strong>Тема:</strong></td>
                    <td>" . htmlspecialchars($subject) . "</td>
                </tr>
                <tr>
                    <td><strong>Сообщение:</strong></td>
                    <td>" . nl2br(htmlspecialchars($message)) . "</td>
                </tr>
                <tr>
                    <td><strong>Дата отправки:</strong></td>
                    <td>" . date('d.m.Y H:i:s') . "</td>
                </tr>
                <tr>
                    <td><strong>IP адрес:</strong></td>
                    <td>" . $_SERVER['REMOTE_ADDR'] . "</td>
                </tr>
            </table>
        </body>
        </html>";
        
        $mail->Body = $html_body;
        
        // Альтернативный текстовый формат
        $text_body = "Новая заявка с сайта Made in Khakassia\n\n";
        $text_body .= "Имя: " . $name . "\n";
        $text_body .= "Email: " . $email . "\n";
        $text_body .= "Телефон: " . $phone . "\n";
        $text_body .= "Тема: " . $subject . "\n\n";
        $text_body .= "Сообщение:\n" . $message . "\n\n";
        $text_body .= "---\n";
        $text_body .= "Отправлено: " . date('d.m.Y H:i:s') . "\n";
        $text_body .= "IP адрес: " . $_SERVER['REMOTE_ADDR'];
        
        $mail->AltBody = $text_body;
        
        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Сообщение успешно отправлено']);
        
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Ошибка при отправке: ' . $mail->ErrorInfo]);
    }
    
} else {
    // Используем встроенную функцию mail() с дополнительными заголовками для SMTP
    $email_subject = 'Новая заявка с сайта: ' . $subject;
    
    // HTML версия письма
    $html_body = "
    <html>
    <head>
        <meta charset='UTF-8'>
    </head>
    <body>
        <h2>Новая заявка с сайта Made in Khakassia</h2>
        <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>
            <tr>
                <td><strong>Имя:</strong></td>
                <td>" . htmlspecialchars($name) . "</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>" . htmlspecialchars($email) . "</td>
            </tr>
            <tr>
                <td><strong>Телефон:</strong></td>
                <td>" . htmlspecialchars($phone) . "</td>
            </tr>
            <tr>
                <td><strong>Тема:</strong></td>
                <td>" . htmlspecialchars($subject) . "</td>
            </tr>
            <tr>
                <td><strong>Сообщение:</strong></td>
                <td>" . nl2br(htmlspecialchars($message)) . "</td>
            </tr>
            <tr>
                <td><strong>Дата отправки:</strong></td>
                <td>" . date('d.m.Y H:i:s') . "</td>
            </tr>
            <tr>
                <td><strong>IP адрес:</strong></td>
                <td>" . $_SERVER['REMOTE_ADDR'] . "</td>
            </tr>
        </table>
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
        echo json_encode(['success' => true, 'message' => 'Сообщение успешно отправлено']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при отправке сообщения']);
    }
}
?>
