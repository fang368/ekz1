<?php
require 'vendor/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Подготовка запроса с фильтром по статусу, если фильтр установлен
$sql = "SELECT * FROM orders WHERE user_id = '$user_id'";
if (!empty($status_filter)) {
    $sql .= " AND status = '$status_filter'";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Ваши заказы</title>
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
        <div class="profile__left-wrapper">
            <form action="profile.php">
                <button type="submit" class="btn btn-primary btn_profile">Профиль</button>
            </form>

            <form action="orders.php">
                <button type="submit" class="btn btn-primary btn_profile">Ваши заявки</button>
            </form>

            <form action="create_orders.php">
                <button type="submit" class="btn btn-primary btn_profile">Сформировать заявку</button>
            </form>
        </div>

        <div class="profile__right-wrapper">
            <div class="orders__header">
                <h2>Ваши заказы</h2>

                <!-- Форма фильтрации по статусу заказа -->
                <form action="orders.php" method="GET" class="filter__form">
                    <label for="status" class="filter__label">Фильтр по статусу:</label>
                    <select name="status" id="status" class="form-select filter__select">
                        <option value="">Все</option>
                        <option value="Новое" <?= $status_filter == 'Новое' ? 'selected' : '' ?>>Новое</option>
                        <option value="Подтверждено" <?= $status_filter == 'Подтверждено' ? 'selected' : '' ?>>Подтверждено</option>
                        <option value="Отклонено" <?= $status_filter == 'Отклонено' ? 'selected' : '' ?>>Отклонено</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn_max-width">Применить</button>
                </form>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <!-- Список заказов -->
                <ul class="orders__list">
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <li class="order">
                            <div class="order__date">Дата посещения: <?= $order['order_date'] ?></div>
                            <div class="order__status">Статус заявки: <?= $order['status'] ?></div>
                            <div class="order__description">Причина обращения: <?= $order['description'] ?></div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="no-orders">Заявок нет</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="js/bootstrap.bundle.js"></script>
</body>
</html>
