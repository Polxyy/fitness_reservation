<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = connect_database();

if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $reservation_id = $_GET['cancel'];
    $stmt = mysqli_prepare($conn, 
        "UPDATE reservations SET status = 'cancelled' 
         WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $reservation_id, $user_id);
    mysqli_stmt_execute($stmt);
}


$stmt = mysqli_prepare($conn, 
    "SELECT r.id, r.reservation_date, r.reservation_time, r.status, f.name AS fitness_class_name
     FROM reservations r
     LEFT JOIN fitness_classes f ON r.fitness_class = f.id
     WHERE r.user_id = ? AND r.status != 'cancelled'
     ORDER BY r.reservation_date, r.reservation_time");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Моите Резервации</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Моите Резервации</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Клас</th>
                    <th>Дата</th>
                    <th>Час</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['fitness_class_name']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                        <td>
                            <a href="?cancel=<?php echo $reservation['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Сигурни ли сте, че искате да отмените тази резервация?')">
                                Отмени
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Обратно към таблото</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
