document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем данные из формы
            const formData = {
                name: form.querySelector('[name="name"]').value,
                phone: form.querySelector('[name="phone"]').value,
                email: form.querySelector('[name="email"]')?.value || '',
                company: form.querySelector('[name="company"]')?.value || '',
                message: form.querySelector('[name="message"]')?.value || ''
            };

            // Показываем загрузку
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Отправка...';
            submitBtn.disabled = true;

            // Отправка на сервер
            fetch('https://www.madeinkhakassia.ru.spaceweb.ru/mail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка сети');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Заявка успешно отправлена!');
                    form.reset();
                } else {
                    throw new Error(data.error || 'Ошибка сервера');
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка отправки: ' + error.message);
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
