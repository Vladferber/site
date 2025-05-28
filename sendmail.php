<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed']);
    exit;
}

$required_fields = ['name', 'phone', 'email', 'message'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Field $field is required"]);
        exit;
    }
}

$name = htmlspecialchars($_POST['name']);
$phone = htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

$to = 'fondrh@mail.ru';
$subject = 'Новая заявка с сайта madeinkhakassia.ru';
$headers = "From: mail@madeinkhakassia.ru\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$email_body = "Имя: $name\nТелефон: $phone\nEmail: $email\n\nСообщение:\n$message";

if (mail($to, $subject, $email_body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Сообщение успешно отправлено']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке сообщения']);
}
?>
