<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connect_database();
$user_id = $_SESSION['user_id'];

$query = "SELECT id FROM fitness_classes";
$result = mysqli_query($conn, $query);
$valid_fitness_class_ids = [];

while ($row = mysqli_fetch_assoc($result)) {
    $valid_fitness_class_ids[] = $row['id'];
}

mysqli_free_result($result);

$fitness_class_id = sanitize_input($_POST['fitness_class']);
$date = sanitize_input($_POST['date']);
$time = sanitize_input($_POST['time']);

if (!in_array($fitness_class_id, $valid_fitness_class_ids)) {
    header("Location: create_reservation.php?error=Моля, изберете валиден фитнес клас");
    exit();
} elseif (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    header("Location: create_reservation.php?error=Моля, изберете валидна дата");
    exit();
} elseif (empty($time) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
    header("Location: create_reservation.php?error=Моля, изберете валиден час");
    exit();
}

$check_stmt = mysqli_prepare($conn, 
    "SELECT id FROM reservations 
     WHERE user_id = ? AND reservation_date = ? AND reservation_time = ? AND status != 'cancelled'");
mysqli_stmt_bind_param($check_stmt, "iss", $user_id, $date, $time);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    header("Location: create_reservation.php?error=Вече имате резервация по това време");
    exit();
}

$stmt = mysqli_prepare($conn, 
    "INSERT INTO reservations (user_id, fitness_class, reservation_date, reservation_time, status) 
     VALUES (?, ?, ?, ?, 'scheduled')");
mysqli_stmt_bind_param($stmt, "isss", $user_id, $fitness_class_id, $date, $time);

if (mysqli_stmt_execute($stmt)) {
    header("Location: create_reservation.php?success=true");
    exit();
} else {
    header("Location: create_reservation.php?error=Възникна грешка при създаването на резервацията.");
    exit();
}

mysqli_close($conn);
?>
