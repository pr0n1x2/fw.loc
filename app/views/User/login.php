<h2>Авторизация</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="/user/login">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" name="login" value="<?=prevValue('login')?>" class="form-control" id="login" placeholder="Логин">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
            </div>
            <button type="submit" class="btn btn-default">Войти</button>
        </form>
    </div>
</div>