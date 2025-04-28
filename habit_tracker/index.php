<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            $name = trim($_POST['name']);
            $frequency = $_POST['frequency'];
            $start_date = $_POST['start_date'];

            if ($name && ($frequency === 'daily' || $frequency === 'weekly') && $start_date) {
                $stmt = $pdo->prepare("INSERT INTO Habits (name, frequency, start_date) VALUES (?, ?, ?)");
                $stmt->execute([$name, $frequency, $start_date]);
            }
            header('Location: index.php');
            exit;
        }

        if ($action === 'edit') {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name']);
            $frequency = $_POST['frequency'];
            $start_date = $_POST['start_date'];

            if ($id && $name && ($frequency === 'daily' || $frequency === 'weekly') && $start_date) {
                $stmt = $pdo->prepare("UPDATE Habits SET name = ?, frequency = ?, start_date = ? WHERE id = ?");
                $stmt->execute([$name, $frequency, $start_date, $id]);
            }
            header('Location: index.php');
            exit;
        }

        if ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM Habits WHERE habit_id = ?");
                $stmt->execute([$id]);
            }
            header('Location: index.php');
            exit;
        }

        if ($action === 'mark_done') {
            $habit_id = (int)$_POST['habit_id'];
            $completion_date = $_POST['completion_date'];

            if ($habit_id && $completion_date) {
                $stmt = $pdo->prepare("INSERT INTO habit_completions (habit_id, completion_date, completed) VALUES (?, ?, 1)
                    ON DUPLICATE KEY UPDATE completed = 1");
                $stmt->execute([$habit_id, $completion_date]);
            }
            header('Location: index.php');
            exit;
        }
    }
}

$stmt = $pdo->query("SELECT * FROM Habits ORDER BY habit_id DESC");
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);

function isCompleted($pdo, $habit_id, $date) {
    $stmt = $pdo->prepare("SELECT completed FROM habit_completions WHERE habit_id = ? AND completion_date = ?");
    $stmt->execute([$habit_id, $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row && $row['completed'] == 1;
}

$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('monday this week'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Habit Tracker</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container">
        <h1>Habit Tracker</h1>

        <section class="add-habit">
            <h2>Add New Habit</h2>
            <form method="POST" action="index.php" class="habit-form">
                <input type="hidden" name="action" value="add" />
                <label for="name">Habit Name:</label>
                <input type="text" id="name" name="name" required />

                <label for="frequency">Frequency:</label>
                <select id="frequency" name="frequency" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required />

                <button type="submit">Add Habit</button>
            </form>
        </section>

        <section class="habit-list">
            <h2>My Habits</h2>
            <?php if (count($habits) === 0): ?>
                <p>No habits added yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Habit Name</th>
                            <th>Frequency</th>
                            <th>Start Date</th>
                            <th>Completion Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($habits as $habit): ?>
                            <tr>
                                <td><?= htmlspecialchars($habit['name']) ?></td>
                                <td><?= htmlspecialchars($habit['frequency']) ?></td>
                                <td><?= htmlspecialchars($habit['start_date']) ?></td>
                                <td>
                                    <?php
                                    $completed = false;
                                    if ($habit['frequency'] === 'daily') {
                                        $completed = isCompleted($pdo, $habit['habit_id'], $today);
                                    } else if ($habit['frequency'] === 'weekly') {
                                        $stmt = $pdo->prepare("SELECT completed FROM habit_completions WHERE habit_id = ? AND completion_date BETWEEN ? AND ? AND completed = 1 LIMIT 1");
                                        $stmt->execute([$habit['habit_id'], $week_start, $today]);
                                        $completed = $stmt->fetch() ? true : false;
                                    }
                                    ?>
                                    <?php if ($completed): ?>
                                        <span class="done">Done</span>
                                    <?php else: ?>
                                        <form method="POST" action="index.php" class="inline-form">
                                            <input type="hidden" name="action" value="mark_done" />
                                            <input type="hidden" name="habit_id" value="<?= $habit['habit_id'] ?>" />
                                            <input type="hidden" name="completion_date" value="<?= $habit['frequency'] === 'daily' ? $today : $week_start ?>" />
                                            <button type="submit" class="mark-done-btn">Mark as Done</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="GET" action="edit.php" class="inline-form">
                                        <input type="hidden" name="id" value="<?= $habit['habit_id'] ?>" />
                                        <button type="submit" class="edit-btn">Edit</button>
                                    </form>
                                    <?php if (!$completed): ?>
                                    <form method="POST" action="index.php" class="inline-form delete-form">
                                        <input type="hidden" name="action" value="delete" />
                                        <input type="hidden" name="id" value="<?= $habit['habit_id'] ?>" />
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
<script src="functions.js"></script>
</body>
</html>