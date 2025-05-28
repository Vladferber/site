<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Получаем данные из формы
$data = json_decode(file_get_contents('php://input'), true);

// Проверяем обязательные поля
if (empty($data['name']) || empty($data['phone'])) {
    echo json_encode(['success' => false, 'error' => 'Заполните имя и телефон']);
    exit;
}

// Настройки письма
$to = 'fondrh@mail.ru';
$subject = 'Новая заявка с сайта Сделано в Хакасии';
$message = "
    Имя: {$data['name']}
    Телефон: {$data['phone']}
    Email: {$data['email'] ?? 'Не указан'}
    Компания: {$data['company'] ?? 'Не указана'}
    Сообщение: {$data['message'] ?? 'Не указано'}
";

// Заголовки письма
$headers = "From: mail@madeinkhakassia.ru\r\n";
$headers .= "Reply-To: mail@madeinkhakassia.ru\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Отправка письма
if (mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    // Логирование ошибки
    error_log('Ошибка отправки письма: ' . print_r(error_get_last(), true));
    echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
}
?>
