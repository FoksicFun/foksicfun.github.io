<?php
include '../includes/db.php';

$search = $_GET['search'] ?? '';
$page = intval($_GET['page'] ?? 1);
$gamesPerPage = 6;
$start = ($page - 1) * $gamesPerPage;

$sql = "SELECT id, title, image, description FROM games";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE title LIKE '%$search%'";
}

$sql .= " ORDER BY id LIMIT $start, $gamesPerPage";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()):
?>
    <div class="game-card" onclick="window.location.href='game-detail.php?id=<?= $row['id'] ?>'">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="game-image">
        <div class="game-info">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
        </div>
    </div>
<?php endwhile; ?>