document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для всех форм на сайте
    const forms = document.querySelectorAll('form[data-form-type="ajax"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('[type="submit"]');
            const originalText = submitButton.textContent;
            const formAction = form.getAttribute('action') || '/sendmail.php';
            const formMethod = form.getAttribute('method') || 'POST';
            
            // Показываем состояние загрузки
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner"></span> Отправка...';
            
            try {
                const response = await fetch(formAction, {
                    method: formMethod,
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Ваша заявка успешно отправлена!', 'success');
                    form.reset();
                    
                    // Сброс reCAPTCHA
                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset();
                    }
                    
                    }
                } else {
                    showAlert(result.message || 'Ошибка при отправке формы', 'error');
                }
            } catch (error) {
                console.error('Form submission error:', error);
                showAlert('Произошла ошибка сети. Попробуйте позже.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    });
    
    // Функция показа уведомлений
    function showAlert(message, type = 'success') {
        // Удаляем предыдущие уведомления
        const existingAlerts = document.querySelectorAll('.form-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Создаем новое уведомление
        const alertDiv = document.createElement('div');
        alertDiv.className = `form-alert form-alert--${type}`;
        alertDiv.textContent = message;
        
        // Добавляем в DOM
        document.body.appendChild(alertDiv);
        
        // Автоматическое скрытие через 5 секунд
        setTimeout(() => {
            alertDiv.classList.add('fade-out');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
});
