<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ошибка</title>
</head>
<body>
<h1>Произошла ошибка</h1>
<p><strong>Код ошибки:</strong> <?= $errno ?></p>
<p><strong>Текст ошибки:</strong> <?= $errstr ?></p>
<p><strong>Файл, в котором произошла ошибка:</strong> <?= $errfile ?></p>
<p><strong>Строка, в которой произошла ошибка:</strong> <?= $errline ?></p>
</body>
</html>