document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('form');
  
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Получаем данные из формы
      const formData = {
        name: form.querySelector('[name="name"]').value,
        phone: form.querySelector('[name="phone"]').value
      };

      // Показываем загрузку
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Отправка...';
      submitBtn.disabled = true;

      // Отправка на сервер
      fetch('https://vh287-fm.sweb.ru/files/madeinkhakassia_ru/public_html/cgi-bin/mail.php', {
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
        alert('Ошибка: ' + error.message);
      })
      .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      });
    });
  }
});
