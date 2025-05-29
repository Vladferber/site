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
$company_name = isset($_POST['company-name']) ? trim($_POST['company-name']) : '';
$contact_person = isset($_POST['contact-person']) ? trim($_POST['contact-person']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$product_type = isset($_POST['product-type']) ? trim($_POST['product-type']) : '';
$production_place = isset($_POST['production-place']) ? trim($_POST['production-place']) : '';
$product_description = isset($_POST['product-description']) ? trim($_POST['product-description']) : '';
$production_percent = isset($_POST['production-percent']) ? trim($_POST['production-percent']) : '';
$additional_info = isset($_POST['additional-info']) ? trim($_POST['additional-info']) : '';

// Валидация обязательных полей
$errors = [];

if (empty($company_name)) {
    $errors[] = 'Название компании обязательно для заполнения';
}

if (empty($contact_person)) {
    $errors[] = 'Контактное лицо обязательно для заполнения';
}

if (empty($phone)) {
    $errors[] = 'Телефон обязателен для заполнения';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Введите корректный email';
}

if (empty($product_type)) {
    $errors[] = 'Тип продукции обязателен для заполнения';
}

if (empty($production_place)) {
    $errors[] = 'Место производства обязательно для заполнения';
}

if (empty($product_description)) {
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
$email_subject = 'Новая заявка на получение логотипа "Сделано в Хакасии"';

// Переводим тип продукции на русский
$product_types = [
    'food' => 'Продукты питания',
    'handmade' => 'Ремесленные изделия',
    'industrial' => 'Промышленные товары',
    'agriculture' => 'Сельхозпродукция',
    'other' => 'Другое'
];
$product_type_ru = isset($product_types[$product_type]) ? $product_types[$product_type] : $product_type;

// Формируем HTML письмо
$html_body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .container { max-width: 700px; margin: 0 auto; background: white; }
        .header { background: linear-gradient(135deg, #2C5F8B 0%, #3A7CB9 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 10px 0 0; opacity: 0.9; }
        .content { padding: 30px; }
        .section { margin-bottom: 25px; }
        .section-title { color: #2C5F8B; font-size: 18px; font-weight: 600; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
        .field-row { display: flex; margin-bottom: 15px; }
        .field-label { font-weight: 600; color: #374151; min-width: 180px; }
        .field-value { color: #6b7280; flex: 1; }
        .description-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #2C5F8B; margin: 10px 0; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .highlight { background: #fef3c7; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🏆 Новая заявка на логотип</h1>
            <p>Программа \"Сделано в Хакасии\"</p>
        </div>
        
        <div class='content'>
            <div class='section'>
                <div class='section-title'>📋 Контактная информация</div>
                <div class='field-row'>
                    <div class='field-label'>Название компании/ИП:</div>
                    <div class='field-value'><strong>" . htmlspecialchars($company_name) . "</strong></div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Контактное лицо:</div>
                    <div class='field-value'>" . htmlspecialchars($contact_person) . "</div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Телефон:</div>
                    <div class='field-value'>" . htmlspecialchars($phone) . "</div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Email:</div>
                    <div class='field-value'>" . htmlspecialchars($email) . "</div>
                </div>
            </div>
            
            <div class='section'>
                <div class='section-title'>🏭 Информация о продукции</div>
                <div class='field-row'>
                    <div class='field-label'>Тип продукции:</div>
                    <div class='field-value'><span class='highlight'>" . htmlspecialchars($product_type_ru) . "</span></div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Место производства:</div>
                    <div class='field-value'>" . htmlspecialchars($production_place) . "</div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Доля производства в Хакасии:</div>
                    <div class='field-value'><strong>" . htmlspecialchars($production_percent) . "%</strong></div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>Описание продукции:</div>
                    <div class='field-value'>
                        <div class='description-box'>" . nl2br(htmlspecialchars($product_description)) . "</div>
                    </div>
                </div>";

if (!empty($additional_info)) {
    $html_body .= "
                <div class='field-row'>
                    <div class='field-label'>Дополнительная информация:</div>
                    <div class='field-value'>
                        <div class='description-box'>" . nl2br(htmlspecialchars($additional_info)) . "</div>
                    </div>
                </div>";
}

$html_body .= "
            </div>
            
            <div class='section'>
                <div class='section-title'>📅 Информация о заявке</div>
                <div class='field-row'>
                    <div class='field-label'>Дата подачи:</div>
                    <div class='field-value'>" . date('d.m.Y в H:i') . "</div>
                </div>
                <div class='field-row'>
                    <div class='field-label'>IP адрес:</div>
                    <div class='field-value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
                </div>
            </div>
        </div>
        
        <div class='footer'>
            <p><strong>Заявка отправлена с официального сайта \"Сделано в Хакасии\"</strong></p>
            <p>Для ответа используйте email: " . htmlspecialchars($email) . "</p>
            <p>Стандартный срок рассмотрения: до 5 рабочих дней</p>
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
    // Генерируем номер заявки
    $application_number = date('Ymd') . rand(1000, 9999);
    echo json_encode([
        'success' => true, 
        'message' => 'Заявка успешно отправлена!',
        'application_number' => $application_number
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке заявки']);
}
?>
