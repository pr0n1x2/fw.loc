<h2>Добавить файл</h2>

<?= \fw\core\base\View::getMessage() ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="/admin/file/add" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Файл:</label>
                <input type="file" name="file" class="form-control" id="file">
            </div>
            <input type="hidden" name="action" value="send">
            <button type="submit" class="btn btn-default">Загрузить файл</button>
        </form>
    </div>
</div>