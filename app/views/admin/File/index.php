<h2>Список моих файлов</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-12">
        <a href="/admin/file/add" class="btn btn-default">Добавить файл</a>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Файл</th>
        <th>Скачать</th>
        <th>Удалить</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($files as $file) : ?>
        <tr>
            <th scope="row"><?= h($file->id) ?></th>
            <td><?= h($file->name) ?></td>
            <td><a href="/admin/file/download?id=<?=h($file->id)?>">скачать</a></td>
            <td><a href="/admin/file/delete?id=<?=h($file->id)?>">удалить</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php if ($pagination->countPages > 1) : ?>
    <?=$pagination?>
<?php endif; ?>