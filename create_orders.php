<?php
require 'vendor/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$errors = [];
$description = '';
$visit_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    $visit_date = trim($_POST['visit_date']);

    if (empty($description)) {
        $errors[] = 'Пожалуйста, укажите причину обращения';
    }

    if (empty($visit_date)) {
        $errors[] = 'Пожалуйста, выберите дату посещения';
    }

    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $insert_query = "INSERT INTO orders (user_id, description, order_date, status) VALUES ('$user_id', '$description', '$visit_date', 'Новое')";

        if (mysqli_query($conn, $insert_query)) {
            header('Location: orders.php');
            exit();
        } else {
            $errors[] = 'Ошибка при сохранении заявки: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Создать заявку</title>
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
            <h2>Создать заявку</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="create_orders.php" method="POST" class="form_create_order">
                <div class="form-group">
                    <label for="description" class="form-label">Причина обращения</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="visit_date" class="form-label">Дата посещения</label>
                    <input type="date" class="form-control" id="visit_date" name="visit_date" value="<?= htmlspecialchars($visit_date) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Создать заявку</button>
            </form>
        </div>
    </div>
</section>

<script src="js/bootstrap.bundle.js"></script>
</body>
</html>
