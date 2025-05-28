<?php
header('Content-Type: application/json');

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неправильный метод запроса']);
    exit;
}

// Получение данных из формы
$data = json_decode(file_get_contents('php://input'), true);

// Проверка данных
if (empty($data)) {
    echo json_encode(['success' => false, 'message' => 'Нет данных формы']);
    exit;
}

// Настройки почты
$to = 'ваша_почта@example.com'; // Ваш email для получения заявок
$subject = 'Новая заявка на логотип "Сделано в Хакасии"';
$from = 'noreply@ваш_домен.ru'; // Email отправителя (лучше использовать домен вашего сайта)

// Формирование тела письма
$message = "Новая заявка на получение логотипа \"Сделано в Хакасии\"\n\n";
$message .= "Компания: " . htmlspecialchars($data['company-name']) . "\n";
$message .= "Контактное лицо: " . htmlspecialchars($data['contact-person']) . "\n";
$message .= "Телефон: " . htmlspecialchars($data['phone']) . "\n";
$message .= "Email: " . htmlspecialchars($data['email']) . "\n\n";
$message .= "Информация о продукции:\n";
$message .= "Тип продукции: " . htmlspecialchars($data['product-type']) . "\n";
$message .= "Место производства: " . htmlspecialchars($data['production-place']) . "\n";
$message .= "Доля производства в Хакасии: " . htmlspecialchars($data['production-percent']) . "%\n";
$message .= "Описание продукции:\n" . htmlspecialchars($data['product-description']) . "\n\n";
$message .= "Дополнительная информация:\n" . htmlspecialchars($data['additional-info']) . "\n";

// Заголовки письма
$headers = "From: $from\r\n";
$headers .= "Reply-To: " . htmlspecialchars($data['email']) . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Отправка письма
$mailSent = mail($to, $subject, $message, $headers);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Заявка успешно отправлена!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже.']);
}
?>
