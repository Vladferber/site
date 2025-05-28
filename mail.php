<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Получаем данные
$data = json_decode(file_get_contents('php://input'), true);

// Проверка данных
if (empty($data['name']) || empty($data['phone'])) {
    echo json_encode(['success' => false, 'error' => 'Заполните имя и телефон']);
    exit;
}

// Настройки письма
$to = 'fondrh@mail.ru';
$subject = 'Новая заявка с сайта';
$message = "Имя: {$data['name']}\nТелефон: {$data['phone']}\n";
if (!empty($data['message'])) $message .= "Сообщение: {$data['message']}";

// Заголовки
$headers = "From: mail@madeinkhakassia.ru\r\n";
$headers .= "Reply-To: mail@madeinkhakassia.ru\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Отправка (используем mail() для простоты)
if (mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
}
?>
