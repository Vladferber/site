document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: form.querySelector('[name="name"]').value,
                phone: form.querySelector('[name="phone"]').value,
                email: form.querySelector('[name="email"]').value || '',
                message: form.querySelector('[name="message"]').value || ''
            };

            fetch('https://ваш-домен.spaceweb.ru/mail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Заявка отправлена!');
                    form.reset();
                } else {
                    alert('Ошибка: ' + (data.error || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                alert('Ошибка отправки: ' + error.message);
            });
        });
    }
});
