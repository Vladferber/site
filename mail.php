<?php
header("Access-Control-Allow-Origin: *"); // Разрешить запросы с любого домена (для теста)
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Разрешить POST
header("Content-Type: application/json"); // Указать тип ответа

// Если запрос OPTIONS (preflight CORS) — завершаем скрипт
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(["error" => "Метод не разрешён. Используйте POST."]));
}

// Далее ваш код обработки формы...
header("Access-Control-Allow-Origin: https://www.madeinkhakassia.ru");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Предварительный CORS-запрос (preflight)
}

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
