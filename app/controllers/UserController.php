<?php

namespace app\controllers;

use app\models\User;
use fw\core\base\View;
use Hautelook\Phpass\PasswordHash;

class UserController extends AppController
{
    public function singupAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            $user->load($_POST);

            if ($user->validate()) {
                $passwordHasher = new PasswordHash(8, false);
                $user->attributes['password'] = $passwordHasher->HashPassword($user->attributes['password']);

                if ($user->save()) {
                    View::setMessage("Регистрация прошла успешно");
                    redirect('/user/login');
                }
            } else {
                View::setMessage($user->getErrors(), false);
            }
        }

        View::setMeta('Регистрация');
    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            $user = new User();

            if ($user->login()) {
                redirect('/admin/user/profile');
            } else {
                View::setMessage($user->getErrors(), false);
            }
        }

        View::setMeta('Авторизация');
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        View::setMessage("Вы вышли из системы");

        redirect('/user/login');
    }
}
