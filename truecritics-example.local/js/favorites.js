function removeFromFavorites(event, gameId) {
    event.stopPropagation();
    const userId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;

    if (!userId) {
        alert('Вы должны войти, чтобы изменять избранное');
        window.location.href = 'login.php';
        return;
    }

    fetch('api/remove-from-favorites.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'game_id=' + gameId + '&user_id=' + userId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const card = document.querySelector(`.game-card[data-game-id="${gameId}"]`);
            if (card) card.remove();

            const list = document.getElementById('favoritesList');
            if (list && list.children.length === 0) {
                list.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">Ваше избранное пусто</p>';
            }
        } else {
            console.error("Ошибка:", data.message);
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
}