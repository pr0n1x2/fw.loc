<table class="table">
    <thead>
    <tr>
        <th>Файл</th>
        <th>Владелец</th>
        <th>Скачать</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($files as $file) : ?>
        <tr>
            <td><?= h($file->name) ?></td>
            <td><?= h($file->username) ?></td>
            <td><a href="/main/download?id=<?=h($file->id)?>">скачать</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>