<h2>Список пользователей</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-12">
        <a href="/admin/user/add" class="btn btn-default">Добавить пользователя</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>E-mail</th>
            <th><a href="/admin/user/?sort=<?=$sortParam?>">Полных лет</a></th>
            <th>Возраст</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) : ?>
    <tr>
        <th scope="row"><?= h($user->id) ?></th>
        <td><?= h($user->name) ?></td>
        <td><?= h($user->email) ?></td>
        <td><?= h($user->age) ?></td>
        <td><?= h($user->adult) ?></td>
        <td><?= h($user->role) ?></td>
        <td><a href="/admin/user/edit?id=<?=h($user->id)?>">редактировать</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php if ($pagination->countPages > 1) : ?>
    <?=$pagination?>
<?php endif; ?>