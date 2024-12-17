<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch fitness classes from the database
$conn = connect_database();
$query = "SELECT id, name FROM fitness_classes";
$result = mysqli_query($conn, $query);
$fitness_classes = [];

while ($row = mysqli_fetch_assoc($result)) {
    $fitness_classes[$row['id']] = $row['name'];
}

mysqli_free_result($result);
mysqli_close($conn);

$selected_fitness_class = isset($_GET['fitness_class']) ? $_GET['fitness_class'] : '';
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Създаване на Резервация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Създаване на Резервация</div>
                    <div class="card-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">Резервацията е създадена успешно!</div>
                        <?php elseif (isset($_GET['error'])): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        <?php endif; ?>

                        <form action="create_reservation_handler.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Фитнес клас</label>
                                <select name="fitness_class" class="form-select" required>
                                    <option value="" disabled selected>Изберете клас</option>
                                    <?php foreach ($fitness_classes as $id => $class): ?>
                                        <option value="<?php echo $id; ?>" 
                                            <?php echo $selected_fitness_class == $id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($class); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Дата</label>
                                <input type="date" name="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Час</label>
                                <input type="time" name="time" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Създай резервация</button>
                            <a href="dashboard.php" class="btn btn-secondary">Назад</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
