<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM habits WHERE habit_id = ?");
    $stmt->execute([$id]);
    $habit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$habit) {
        header('Location: index.php');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $frequency = $_POST['frequency'];
        $start_date = $_POST['start_date'];

        if ($id && $name && ($frequency === 'daily' || $frequency === 'weekly') && $start_date) {
            $stmt = $pdo->prepare("UPDATE habits SET name = ?, frequency = ?, start_date = ? WHERE habit_id = ?");
            $stmt->execute([$name, $frequency, $start_date, $id]);
        }
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Habit</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container">
        <h1>Edit Habit</h1>
        <form method="POST" action="edit.php" class="habit-form">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?= htmlspecialchars($habit['habit_id']) ?>" />

            <label for="name">Habit Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($habit['name']) ?>" required />

            <label for="frequency">Frequency:</label>
            <select id="frequency" name="frequency" required>
                <option value="daily" <?= $habit['frequency'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="weekly" <?= $habit['frequency'] === 'weekly' ? 'selected' : '' ?>>Weekly</option>
            </select>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($habit['start_date']) ?>" required />

            <button type="submit">Save Changes</button>
            <a href="index.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
