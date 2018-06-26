<h2>Регистрация</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="/user/singup">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" name="login" value="<?=prevValue('login')?>" class="form-control" id="login" placeholder="Логин">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" value="<?=prevValue('password')?>" class="form-control" id="password" placeholder="Пароль">
            </div>
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" name="name" value="<?=prevValue('name')?>" class="form-control" id="name" placeholder="Имя">
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" name="email" value="<?=prevValue('email')?>" class="form-control" id="email" placeholder="E-mail">
            </div>
            <div class="form-group">
                <label for="birthdate">Дата рождения:</label>
                <input type="text" name="birthdate" value="<?=prevValue('birthdate')?>" class="form-control" id="birthdate" placeholder="Дата рождения">
            </div>
            <button type="submit" class="btn btn-default">Зарегистрироваться</button>
        </form>
    </div>
</div>

<script src="/js/jquery.inputmask.bundle.min.js"></script>
<script>
    var FormInputMask = function () {

        var handleInputMasks = function () {
            $("#birthdate").inputmask("y-m-d", {
                "placeholder": "гггг-мм-дд"
            });
        }

        return {
            init: function () {
                handleInputMasks();
            }
        };

    }();

    $(document).ready(function() {
        FormInputMask.init();
    });
</script>