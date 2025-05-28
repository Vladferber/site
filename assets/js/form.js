document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Получаем данные формы
            const formData = {
                name: form.querySelector('[name="name"]').value,
                phone: form.querySelector('[name="phone"]').value,
                message: form.querySelector('[name="message"]')?.value || ''
            };

            // Изменяем кнопку
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Отправка...';
            submitBtn.disabled = true;

            try {
                // Отправляем данные
                const response = await fetch('https://fondrhmail.spaceweb.ru/mail.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Заявка успешно отправлена!');
                    form.reset();
                } else {
                    throw new Error(result.error || 'Ошибка сервера');
                }
            } catch (error) {
                console.error('Ошибка:', error);
                alert('Ошибка отправки: ' + error.message);
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});
