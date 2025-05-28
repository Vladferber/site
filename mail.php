<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Получаем данные
$data = json_decode(file_get_contents('php://input'), true);

// Проверка данных
if (empty($data['name']) || empty($data['phone'])) {
  echo json_encode(['success' => false, 'error' => 'Заполните все поля']);
  exit;
}

// Настройки письма
$to = 'fondrh@mail.ru';
$subject = 'Новая заявка с сайта';
$message = "
  Имя: {$data['name']}
  Телефон: {$data['phone']}
  Дата: " . date('d.m.Y H:i');

// Заголовки
$headers = "From: mail@madeinkhakassia.ru\r\n";
$headers .= "Reply-To: mail@madeinkhakassia.ru\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Отправка
if (mail($to, $subject, $message, $headers)) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
}
?>
