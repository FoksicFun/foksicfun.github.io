$(document).ready(function () {
    const form = $('#feedbackForm');
    const modal = $('#successModal');
    const feedbackList = $('#feedbackList');

    // Отправка формы через POST
    form.on('submit', function (e) {
        e.preventDefault();

        const data = {
            name: $('#name').val().trim(),
            email: $('#email').val().trim(),
            message: $('#message').val().trim()
        };

        if (!data.name || !data.email || !data.message) {
            alert("Заполните все поля");
            return;
        }

        $.post('ajax.php', data, function (response) {
            if (response.status === 'success') {
                modal.show();
                form.trigger('reset');
                loadFeedback();
            } else {
                alert("Ошибка: " + response.message);
            }
        }, 'json');
    });

    // Закрытие модального окна
    $('#closeModalBtn, .close-btn').on('click', function () {
        modal.hide();
    });

    // Получение списка отзывов через GET
    function loadFeedback() {
        $.get('ajax.php', function (response) {
            if (response.status === 'success') {
                feedbackList.empty();
                response.data.forEach(feedback => {
                    feedbackList.append(`
                        <div class="feedback-item">
                            <h4>${feedback.name} (${feedback.email})</h4>
                            <p>${feedback.message}</p>
                            <small>${new Date(feedback.created_at).toLocaleString()}</small>
                        </div>
                    `);
                });
            }
        }, 'json');
    }

    // Автообновление каждые 10 секунд
    setInterval(loadFeedback, 10000);

    // При клике по аватару → переход в избранное
    $('#userAvatar').on('click', function () {
        window.location.href = 'favorites.php';
    });
});