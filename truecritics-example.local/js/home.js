document.addEventListener('DOMContentLoaded', function () {
    const userAvatar = document.getElementById('userAvatar');

    if (userAvatar) {
        userAvatar.addEventListener('click', function () {
            window.location.href = 'favorites.php';
        });
    }
});