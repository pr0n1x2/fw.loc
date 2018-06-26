<h2>Изменить пароль</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="/admin/user/password">
            <div class="form-group">
                <label for="password">Логин:</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
            </div>
            <button type="submit" class="btn btn-default">Изменить</button>
        </form>
    </div>
</div>