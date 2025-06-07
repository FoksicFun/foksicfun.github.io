document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const paginationContainer = document.getElementById('pagination');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            fetch(`api/filter-games.php?search=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('gameList').innerHTML = data;
                    updatePagination(query);
                });
        });
    }

    function updatePagination(searchQuery = '') {
        const url = searchQuery ?
            `api/get-total-games.php?search=${encodeURIComponent(searchQuery)}` :
            `api/get-total-games.php`;

        fetch(url)
            .then(response => response.json())
            .then(totalData => {
                const totalPages = Math.ceil(totalData.total / 6);
                paginationContainer.innerHTML = '';

                if (searchQuery) {
                    // AJAX-пагинация
                    for (let i = 1; i <= totalPages; i++) {
                        const btn = document.createElement('button');
                        btn.className = 'page-btn';
                        btn.textContent = i;
                        btn.dataset.page = i;

                        if (i === 1) btn.classList.add('active');

                        paginationContainer.appendChild(btn);
                    }

                    paginationContainer.querySelectorAll('.page-btn').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const page = this.getAttribute('data-page');
                            fetch(`api/filter-games.php?search=${encodeURIComponent(searchQuery)}&page=${page}`)
                                .then(response => response.text())
                                .then(data => {
                                    document.getElementById('gameList').innerHTML = data;
                                    updatePagination(searchQuery);
                                });
                        });
                    });

                } else {
                    // Обычная пагинация через ссылки
                    paginationContainer.innerHTML = `
                        <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>" class="page-btn">Назад</a><?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-btn<?= $i == $page ? ' active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?><a href="?page=<?= $page + 1 ?>" class="page-btn">Вперёд</a><?php endif; ?>
                    `;
                }
            });
    }

    // Клик по аватару
    const userAvatar = document.getElementById('userAvatar');
    if (userAvatar) {
        userAvatar.addEventListener('click', function () {
            window.location.href = 'favorites.php';
        });
    }
});