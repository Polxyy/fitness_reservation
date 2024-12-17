<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$conn = connect_database();


$query = "SELECT id, name FROM fitness_classes";
$result_classes = mysqli_query($conn, $query);
if (!$result_classes) {
    die('Database query failed: ' . mysqli_error($conn));
}

$stmt = mysqli_prepare($conn, 
    "SELECT r.fitness_class, r.reservation_date, r.reservation_time, f.name AS fitness_class_name 
     FROM reservations r
     LEFT JOIN fitness_classes f ON r.fitness_class = f.id
     WHERE r.user_id = ? AND r.status = 'scheduled'
     ORDER BY r.reservation_date, r.reservation_time");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_reservations = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Табло</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Фитнес Резервации</a>
            <div class="navbar-nav">
                <a class="nav-link" href="create_reservation.php">Нова Резервация</a>
                <a class="nav-link" href="my_reservations.php">Моите Резервации</a>
                <a href="update_profile.php" class="nav-link">Обнови профил</a>
                <a class="nav-link" href="logout.php">Изход</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Добре дошъл, <?php echo htmlspecialchars($user_name); ?>!</h1>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">Предстоящи резервации</div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($result_reservations) > 0): ?>
                            <ul class="list-group">
                                <?php while ($reservation = mysqli_fetch_assoc($result_reservations)): ?>
                                    <li class="list-group-item">
                                        <?php echo htmlspecialchars($reservation['fitness_class_name']); ?>: 
                                        <?php echo htmlspecialchars($reservation['reservation_date']); ?> 
                                        в <?php echo htmlspecialchars($reservation['reservation_time']); ?>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Нямате предстоящи резервации.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Налични фитнес класове</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($result_classes)): ?>
                                <li class="list-group-item">
                                    <a class="text-decoration-none d-inline-block w-100" href="create_reservation.php?fitness_class=<?php echo urlencode($row['id']); ?>">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
