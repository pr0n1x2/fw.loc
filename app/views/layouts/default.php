<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= \fw\core\base\View::$meta['description'] ?>">
    <title><?= \fw\core\base\View::$meta['title'] ?></title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <ul class="nav nav-pills">
            <li><a href="/">Главная</a></li>
            <?php if (!isset($_SESSION['user'])) : ?>
            <li><a href="/user/singup">Зарегистрироваться</a></li>
            <li><a href="/user/login">Войти</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user'])) : ?>
            <li><a href="/admin/user/profile">Мой профиль</a></li>
            <li><a href="/user/logout">Выйти</a></li>
            <?php endif; ?>
        </ul>
        <?=$content?>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
<?php
foreach ($scripts as $script) {
    echo $script;
}
?>
</body>
</html>