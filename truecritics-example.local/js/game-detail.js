document.addEventListener('DOMContentLoaded', function () {
    const favoriteBtn = document.getElementById('favoriteBtn');
    const favoriteIcon = document.getElementById('favoriteIcon');
    const favoriteText = document.getElementById('favoriteText');

    if (!favoriteBtn || !favoriteIcon || !favoriteText) return;

    let isFavorite = window.gameData?.isFavorite || false;
    const gameId = window.gameData?.gameId || null;
    const userId = window.gameData?.userId || null;

    function updateButton() {
        if (isFavorite) {
            favoriteBtn.classList.add('added');
            favoriteIcon.textContent = '✓';
            favoriteText.textContent = 'В избранном';
        } else {
            favoriteBtn.classList.remove('added');
            favoriteIcon.textContent = '❤';
            favoriteText.textContent = 'Добавить в избранное';
        }
    }

    updateButton();

    favoriteBtn.addEventListener('click', function () {
        if (!userId) {
            alert('Авторизуйтесь, чтобы добавить игру в избранное');
            window.location.href = 'login.php';
            return;
        }

        fetch('api/toggle-favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'game_id=' + gameId + '&user_id=' + userId
        })
        .then(response => {
            if (!response.ok) throw new Error("Ошибка сети");

            return response.json();
        })
        .then(data => {
            isFavorite = data.is_favorite;
            updateButton();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при обновлении избранного');
        });
    });

    const userAvatar = document.getElementById('userAvatar');
    if (userAvatar) {
        userAvatar.addEventListener('click', function () {
            window.location.href = 'favorites.php';
        });
    }
});