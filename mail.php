<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Разрешить запросы с любого домена

// Получаем данные из формы
$data = [
    'name' => $_POST['name'] ?? 'Не указано',
    'phone' => $_POST['phone'] ?? 'Не указано',
    'email' => $_POST['email'] ?? 'Не указано',
    'message' => $_POST['message'] ?? 'Не указано'
];

// Настройки письма
$to = "fondrh@mail.ru";
$subject = "Новая заявка с сайта madeinkhakassia.ru";
$message = "
    Имя: {$data['name']}\n
    Телефон: {$data['phone']}\n
    Email: {$data['email']}\n
    Сообщение: {$data['message']}
";
$headers = "From: mail@madeinkhakassia.ru\r\n";
$headers .= "Reply-To: {$data['email']}\r\n";

// Отправляем письмо
if (mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Ошибка отправки']);
}
?>
