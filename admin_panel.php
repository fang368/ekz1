<?php
require 'vendor/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Проверка прав администратора
$sql_role = "SELECT id_role FROM users WHERE id = '$user_id'";
$result_role = mysqli_query($conn, $sql_role);
$row_role = mysqli_fetch_assoc($result_role);

if ($row_role['id_role'] != 2) {
    header('location: index.php');
    exit();
}

// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET status = '$status' WHERE id = '$order_id' AND status = 'Новое'";
    mysqli_query($conn, $update_query);
}

// Подготовка запроса с фильтром по статусу и пагинацией
$sql = "SELECT orders.id, users.full_name, orders.description, orders.order_date, orders.status 
        FROM orders 
        JOIN users ON orders.user_id = users.id";
if (!empty($status_filter)) {
    $sql .= " WHERE orders.status = '$status_filter'";
}
$sql .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

// Получение общего количества записей для пагинации
$count_sql = "SELECT COUNT(*) AS total FROM orders";
if (!empty($status_filter)) {
    $count_sql .= " WHERE status = '$status_filter'";
}
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Панель администратора</title>
</head>
<body>

<section class="main-section">
    <div class="container main-section__container">
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form action="vendor/logout.php" class="d-flex" role="search">
                        <button class="btn btn-outline-danger" type="submit">Выйти</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <div class="container profile__container">
        <div class="profile__right-wrapper admin__right-wrapper">
            <div class="orders__header">
                <h2>Все заявки</h2>

                <!-- Форма фильтрации по статусу заявки -->
                <form action="admin_panel.php" method="GET" class="filter__form">
                    <label for="status" class="filter__label">Фильтр по статусу:</label>
                    <select name="status" id="status" class="form-select filter__select">
                        <option value="">Все</option>
                        <option value="новое" <?= $status_filter == 'Новое' ? 'selected' : '' ?>>Новое</option>
                        <option value="подтверждено" <?= $status_filter == 'Подтверждено' ? 'selected' : '' ?>>Подтверждено</option>
                        <option value="отклонено" <?= $status_filter == 'Отклонено' ? 'selected' : '' ?>>Отклонено</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn_max-width">Применить</button>
                </form>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <!-- Список заявок -->
                <ul class="orders__list">
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <li class="order">
                            <div class="order__fullname">ФИО: <?= htmlspecialchars($order['full_name']) ?></div>
                            <div class="order__date">Дата посещения: <?= htmlspecialchars($order['order_date']) ?></div>
                            <div class="order__status">Статус заявки: <?= htmlspecialchars($order['status']) ?></div>
                            <div class="order__description">Причина обращения: <?= htmlspecialchars($order['description']) ?></div>
                            <?php if ($order['status'] == 'Новое'): ?>
                                <form action="admin_panel.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" class="form-select">
                                        <option value="Подтверждено">Подтверждено</option>
                                        <option value="Отклонено">Отклонено</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Изменить статус</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <!-- Пагинация -->
                <nav aria-label="Page navigation" class="pagination__container">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="admin_panel.php?page=<?= $i ?>&status=<?= $status_filter ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else: ?>
                <div class="no-orders">Заявок нет</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="js/bootstrap.bundle.js"></script>
</body>
</html>
