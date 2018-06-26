<?php

namespace app\controllers\admin;

use app\models\File;
use fw\core\base\View;
use fw\libs\Pagination;

class FileController extends AppController
{
    public function indexAction()
    {
        $model = new File();

        $totalCount = $model->getTotalCount();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 10;

        $pagination = new Pagination($page, $totalCount, $perpage);
        $start = $pagination->getStart();

        $files = $model->findAll($start, $perpage, null, "WHERE user_id = " . $_SESSION['user']['user_id']);

        $this->set(compact('files', 'pagination'));

        View::setMeta('Список моих файлов');
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            if ($_FILES['file']['error'] == 4) {
                View::setMessage("Вы не выбрали файл", false);
            } else {
                $file = new File();
                $file->attributes['user_id'] = $_SESSION['user']['user_id'];

                if ($file->validate()) {
                    if ($file->save()) {
                        View::setMessage("Файл был успешно добавлен");
                        redirect('/admin/file/');
                    }
                } else {
                    View::setMessage($file->getErrors(), false);
                }
            }
        }

        View::setMeta('Добавить файл');
    }

    public function downloadAction()
    {
        $this->layout = false;

        $model = new File();
        $file = $model->get($_GET['id']);
        $model->download($file->file, $file->name);

        die();
    }

    public function deleteAction()
    {
        $this->layout = false;

        $model = new File();
        $file = $model->get($_GET['id']);

        if ($file->user_id == $_SESSION['user']['user_id']) {
            $model->delete($file);
            View::setMessage("Файл был удален");
        } else {
            View::setMessage("Невозможно удалить файл", false);
        }

        redirect('/admin/file/');
    }
}
