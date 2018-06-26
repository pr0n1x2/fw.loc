<?php

namespace app\controllers;

use app\models\Main;
use app\models\File;
use fw\core\App;
use fw\core\base\View;
use R;

class MainController extends AppController
{
    public function indexAction()
    {
        $model = new Main();

        $result = R::getAll("SELECT f.*, u.name AS username FROM files f, users u 
            WHERE f.user_id = u.id ORDER BY f.id DESC");

        $files = R::convertToBeans('files', $result);

        $this->set(compact('files'));

        View::setMeta('Главная');
    }

    public function downloadAction()
    {
        $this->layout = false;

        $model = new File();
        $file = $model->get($_GET['id']);
        $model->download($file->file, $file->name);

        die();
    }
}
