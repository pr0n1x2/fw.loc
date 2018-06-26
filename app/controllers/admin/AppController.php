<?php

namespace app\controllers\admin;

use fw\core\base\Controller;
use fw\core\base\View;

class AppController extends Controller
{
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);

        if (!isset($_SESSION['user'])) {
            View::setMessage("Вы не авторизованы", false);
            redirect('/user/login');
        }
    }

    public function isAdmin()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] != 'admin') {
            redirect('/admin/user/profile');
        }
    }
}
